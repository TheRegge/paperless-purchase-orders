<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_orders extends CI_Model {

	/**
	 * retrieve on or all Purchase Order and all associated
	 * items from the database
	 * @param  string  $ssid     The user Senior System ID
	 * @param  boolean $isAdmin  True if use has admin status
	 * @param  int 	   $poNumber The poNumber
	 * @return array             Purchase order details with nested items array
	 */
	public function get($ssid, $isAdmin, $poNumber=0)
	{
		if ( $poNumber > 0 )
		{
			$this->db->where('poNumber', $poNumber );
		}
		
		if ( ! $isAdmin )
		{
			$this->db->where('ssid', $ssid);
		}

		$this->db->order_by('date', 'DESC');

		$query = $this->db->get('purchaseOrders');
		
		$pos   = $query->result_array();
		$l     = count( $pos );

		$returnArray = array(
			'success'        => true,
			'purchaseOrders' => array()
		);

		for ($i=0; $i<$l; $i++)
		{
			// Department
			$pos[$i]['department'] = $this->_getColById( $pos[$i]['department_id'], 'departments', 'name' );

			// ShipToLocation
			$pos[$i]['shipToLocation'] = $this->_getColById( $pos[$i]['shipToLocations_id'], 'shipToLocations' );

			// Purchase Items
			$this->db->where('purchaseOrder_poNumber', $pos[$i]['poNumber']);
			$query = $this->db->get('items');
			$items = $query->result_array();
			$pos[$i]['items'] = $items;
			array_push( $returnArray['purchaseOrders'], $pos[$i]);
		}
		return $returnArray;
	}

	public function getBy ($params )
	{
		if ( ! $params )
		{
			return $this->get(false);
		}

		//$this->db->where( 'teacher', $params['teacher'] );
		foreach ($params as $key => $value)
		{
			if ($value)
			{
				$this->db->where( $key, $value);
			}
		}

		$query = $this->db->get('purchaseOrders');
		$pos   = $query->result_array();
		return $pos;
		
	}

	/**
	 * Avoid simple query boilerplate
	 * @param  int $id        the id
	 * @param  string $tableName the table to query
	 * @param  string $col       the column name to query
	 * @return mixed
	 */
	protected function _getColById($id, $tableName, $col = false)
	{
		$this->db->where('id', $id );
		$query = $this->db->get($tableName);
		if ( $query->num_rows() > 0 )
		{
			$row = $query->row();
			if ( $col )
			{
				return $row->$col;
			}
			else
			{
				return $row;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Save a purchase order
	 * 
	 * Insert general po info, then get the
	 * returned 'insert' id and use it to 
	 * save the items ordered on the po.
	 * 
	 * @param   array $po Purchase Order data
	 * @return  array     The save operation results
	 */
	public function save_po($po)
	{		
		$poData = array(
			'poNumber'                  => $po->poNumber,
			'ssid'                      => $po->ssid,
			'department_id'             => $po->department_id,
			'date'						=> $this->_formatDateForMysql( $po->date ),
			'vendor'                    => $po->vendor,
			'shipToLocations_id'        => $po->shipTo,
			'teacher'                   => $po->teacher,
			'divDirector'               => $po->divDirector,
			'shippingAndHandling'       => $po->shippingAndHandling,
			'shippingAndHandlingBudget' => $po->shippingAndHandlingBudget,
			'totalAmount'               => $po->totalAmount
		);


		if ( isset($po->dateRequired) )
		{
			$poData['dateRequired'] = $this->_formatDateForMysql( $po->dateRequired );
		}
		else
		{
			$poData['dateRequired'] = null;
		}

		if ( isset($po->remarks) )
		{
			$poData['remarks'] = $po->remarks;
		}
		else
		{
			$poData['remarks'] = null;
		}

		if ( count( $po->items ) )
		{
			$insertPo = $this->_insertPo( $poData, $po->items );
			if ( $insertPo['success'] )
			{
				return $insertPo;
			}
		}
		return array( 'success' => false );
	}

	private function _formatDateForMysql ( $d )
	{
		$d = date_parse( $d );
		$s = $d['year'].'-'.$d['month'].'-'.$d['day'];
		return $s;
	}

	public function delete_po ( $poNumber )
	{
		// Delete PO items
		$this->db->where('purchaseOrder_poNumber', $poNumber)
		->delete('items');

		// Delete PO
		$this->db->where('poNumber', $poNumber);
		$query = $this->db->delete('purchaseOrders');

		return $query;
	}

	/**
	 * Save a Purchase Order to the database
	 * @param  array $poData Purchase order data includeing items
	 * @return array         Array with 'success' (boolean) key and 
	 *                             optionally 'items' key if success
	 */
	protected function _insertPo( $poData, $items )
	{
		$query = $this->db->insert( 'purchaseOrders', $poData );

		if ( $this->db->affected_rows() > 0 )
		{
			$insertedItems = array();

			for ( $i=0; $i<count( $items ); $i++ )
			{
				$insert = $this->_insertItem( $poData['poNumber'], $items[$i] );
				if ( $insert > 0 )
				{
					$insertedItems[] = $insert;
				}
			}

			if ( count( $insertedItems ) > 0 )
			{
				return array(
					'poId'    => $poData['poNumber'],
					'success' => true,
					'items'   => $insertedItems
				);
			}
		}
		else
		{
			return array( 'success' => false );
		}
	}

	/**
	 * Save one item
	 * @param  int $poId   the inserted id of the P.O.
	 * @param  array $item item data
	 * @return int         the item inserted id
	 */
	protected function _insertItem( $poId , $item )
	{
		$itemData = array(
			'purchaseOrder_poNumber' => $poId,
			'quantity'               => $item->quantity,
			'unitPrice'              => $item->unitPrice,
			'catalogNo'              => $item->catalogNo,
			'budgetNumber'           => $item->budgetNumber
		);

		if ( isset($item->description) )
		{
			$itemData['description'] = $item->description;
		}
		else
		{
			$itemData['description'] = null;
		}
		$query = $this->db->insert( 'items', $itemData );
		if ( $this->db->affected_rows() === 1 )
		{
			return $this->db->insert_id();
		}
		else
		{
			return 0;
		}
	}

	public function getShipToLocations()
	{
		$query = $this->db->get('shipToLocations');
		return $query->result_array();
	}


}

/* End of file purchase_orders.php */
/* Location: ./application/models/purchase_orders.php */