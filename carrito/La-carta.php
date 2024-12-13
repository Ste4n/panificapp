<?php session_start();
class Cart
{
	protected $cart_contents = array();

	public function __construct()
	{
		// obtener el array del carrito de la sesión
		$this->cart_contents = !empty($_SESSION['cart_contents']) ? $_SESSION['cart_contents'] : NULL;
		if ($this->cart_contents === NULL) {
			// establecer algunos valores base
			$this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
		}
	}

	/**
	 * Contenido del carrito: Devuelve el array completo del carrito
	 * @param    bool
	 * @return    array
	 */
	public function contents()
	{
		// reorganizar el más reciente primero
		$cart = array_reverse($this->cart_contents);

		// eliminar estos para que no causen un problema al mostrar la tabla del carrito
		unset($cart['total_items']);
		unset($cart['cart_total']);

		return $cart;
	}

	/**
	 * Obtener ítem del carrito: Devuelve los detalles de un ítem específico del carrito
	 * @param    string    $row_id
	 * @return    array
	 */
	public function get_item($row_id)
	{
		return (in_array($row_id, array('total_items', 'cart_total'), TRUE) or !isset($this->cart_contents[$row_id]))
			? FALSE
			: $this->cart_contents[$row_id];
	}

	/**
	 * Total de ítems: Devuelve el conteo total de ítems
	 * @return    int
	 */
	public function total_items()
	{
		return $this->cart_contents['total_items'];
	}

	/**
	 * Total del carrito: Devuelve el precio total
	 * @return    int
	 */
	public function total()
	{
		return $this->cart_contents['cart_total'];
	}

	/**
	 * Insertar ítems en el carrito y guardarlos en la sesión
	 * @param    array
	 * @return    bool
	 */
	public function insert($item = array())
	{
		if (!is_array($item) or count($item) === 0) {
			return FALSE;
		} else {
			if (!isset($item['id'], $item['nombre'], $item['precio'], $item['qty'])) {
				return FALSE;
			} else {
				/*
				 * Insertar ítem
				 */
				// preparar la cantidad
				$item['qty'] = (float) $item['qty'];
				if ($item['qty'] == 0) {
					return FALSE;
				}
				// preparar el precio
				$item['precio'] = (float) $item['precio'];
				// crear un identificador único para el ítem insertado en el carrito
				$rowid = md5($item['id']);
				// obtener la cantidad si ya está ahí y agregarla
				$old_qty = isset($this->cart_contents[$rowid]['qty']) ? (int) $this->cart_contents[$rowid]['qty'] : 0;
				// recrear la entrada con identificador único y cantidad actualizada
				$item['rowid'] = $rowid;
				$item['qty'] += $old_qty;
				$this->cart_contents[$rowid] = $item;

				// guardar ítem del carrito
				if ($this->save_cart()) {
					return isset($rowid) ? $rowid : TRUE;
				} else {
					return FALSE;
				}
			}
		}
	}

	/**
	 * Actualizar el carrito
	 * @param    array
	 * @return    bool
	 */
	public function update($item = array())
	{
		if (!is_array($item) or count($item) === 0) {
			return FALSE;
		} else {
			if (!isset($item['rowid'], $this->cart_contents[$item['rowid']])) {
				return FALSE;
			} else {
				// preparar la cantidad
				if (isset($item['qty'])) {
					$item['qty'] = (float) $item['qty'];
					// eliminar el ítem del carrito, si la cantidad es cero
					if ($item['qty'] == 0) {
						unset($this->cart_contents[$item['rowid']]);
						return TRUE;
					}
				}

				// encontrar las claves actualizables
				$keys = array_intersect(array_keys($this->cart_contents[$item['rowid']]), array_keys($item));
				// preparar el precio
				if (isset($item['precio'])) {
					$item['precio'] = (float) $item['precio'];
				}
				// el id y el nombre del producto no deben cambiarse
				foreach (array_diff($keys, array('id', 'nombre')) as $key) {
					$this->cart_contents[$item['rowid']][$key] = $item[$key];
				}
				// guardar datos del carrito
				$this->save_cart();
				return TRUE;
			}
		}
	}

	/**
	 * Guardar el array del carrito en la sesión
	 * @return    bool
	 */
	protected function save_cart()
	{
		$this->cart_contents['total_items'] = $this->cart_contents['cart_total'] = 0;
		foreach ($this->cart_contents as $key => $val) {
			// asegurarse de que el array contenga los índices adecuados
			if (!is_array($val) or !isset($val['precio'], $val['qty'])) {
				continue;
			}

			$this->cart_contents['cart_total'] += ($val['precio'] * $val['qty']);
			$this->cart_contents['total_items'] += $val['qty'];
			$this->cart_contents[$key]['subtotal'] = ($this->cart_contents[$key]['precio'] * $this->cart_contents[$key]['qty']);
		}

		// si el carrito está vacío, eliminarlo de la sesión
		if (count($this->cart_contents) <= 2) {
			unset($_SESSION['cart_contents']);
			return FALSE;
		} else {
			$_SESSION['cart_contents'] = $this->cart_contents;
			return TRUE;
		}
	}

	/**
	 * Eliminar ítem: Elimina un ítem del carrito
	 * @param    int
	 * @return    bool
	 */
	public function remove($row_id)
	{
		// eliminar y guardar
		unset($this->cart_contents[$row_id]);
		$this->save_cart();
		return TRUE;
	}

	/**
	 * Destruir el carrito: Vacía el carrito y destruye la sesión
	 * @return    void
	 */
	public function destroy()
	{
		$this->cart_contents = array('cart_total' => 0, 'total_items' => 0);
		unset($_SESSION['cart_contents']);
	}
}
?>
