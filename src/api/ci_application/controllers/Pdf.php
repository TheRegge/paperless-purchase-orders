<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Underscore\Types\Arrays;

class Pdf extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Purchase_orders', 'poModel');
		$this->load->library('Demo_login/demo_login');
	}

	public function make() {
		$poNumber = $this->input->get('poid');
		if ( $poNumber )
		{
			$current_user = $this->demo_login->get_user_info();
			$ssid         = $current_user['ssid'];
			$isAdmin      = $this->roles->current_user_is_admin();
			$model        = $this->poModel->get($ssid, $isAdmin, $poNumber);

			if ( isset($model['success']) && $model['success'] )
			{
				$po = $model['purchaseOrders'][0];
			}
			$this->_makePDF( $po );
		}
	}

	protected function _makePDF( $po )
	{
		$this->load->library('pdfmaker');

		setlocale(LC_MONETARY, 'en_US.UTF-8');

		// Create a new PDF document
		$pdf = new Pdfmaker(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// Set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('The Demo School');
		$pdf->SetTitle('The Demo School | Purchase Order');
		$pdf->SetSubject('Purchase Order');
		$pdf->SetKeywords('PDF, Purchase, Order');
		
		// set page unit
		$pdf->setPageUnit('pt');
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);

		// remove default footer
		$pdf->setPrintFooter(false);

		// Do NOT remove the Header,
		// this is where the bg image is created

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// ADD A PAGE
		$pdf->AddPage();
		
		// Print the PO Number
		$pdf->SetFont('courier', 'B', 20);
		$html = $po['poNumber'];
		$pdf->writeHTMLCell(0, 20, 452, 26, $html, 0, 1, false, true, 'L', false);

		// Print the Department
		$pdf->SetFont('helvetica', '', 12);
		$html = $po['department'];
		$pdf->writeHTMLCell(0, 20, 456, 52, $html, 0, 1, false, true, 'L', false);

		// Print the Vendor
		$pdf->SetFont('helvetica', '', 8);
		$html = $po['vendor'];
		$pdf->writeHTMLCell(0, 20, 27, 105, $html, 0, 1, false, true, 'L', false);

		// Print the Ship To Location
		$pdf->setFont('helvetica', '', 8);
		$html = '<strong>The Demo School</strong> - <em>' . $po['shipToLocation']->label . '</em><br>';
		$html .= $po['shipToLocation']->address;
		$pdf->writeHTMLCell(0, 20, 326, 105, $html, 0, 1, false, true, 'L', false);

		// Print Date
		$date = $po['date'];
		$html = date( 'M j, Y', strtotime($date));
		$pdf->writeHTMLCell(0, 20, 80, 216, $html, 0, 1, false, true, 'L', false);
		
		// Print Date Required
		if ( $po['dateRequired'] )
		{
			$date = $po['dateRequired'];
			$html = date( 'M j, Y', strtotime($date));
			$pdf->writeHTMLCell(0, 20, 280, 216, $html, 0, 1, false, true, 'L', false);
		}
		
		// Print Budget Number if only one budget used
		$items           = $po['items'];
		$budgets         = array();
		$groupedItems    = Arrays::group($items,'budgetNumber');
		$multipleBudgets = false;

		if ( count( $groupedItems ) === 1 )
		{
			$key = array_keys($groupedItems)[0];
			$html = $key;
			$pdf->writeHTMLCell(0, 20, 480, 216, $html, 0, 1, false, true, 'L', false);
		}
		else
		{
			$multipleBudgets = true;
			$html = "(See Remarks below)";
			$pdf->writeHTMLCell(0, 20, 480, 216, $html, 0, 1, false, true, 'L', false);
		}

		// Print Purchase Items
		$y = 252;
		
		for ($i=0; $i<count($items);$i++)
		{
			$y = $y + 17.25;
			$item = $items[$i];
			// item quantity
			$html = $item['quantity'];
			$pdf->writeHTMLCell(0, 20, 28, $y, $html, 0, 1, false, true, 'L', false);

			// item catalog NO.
			$html = $item['catalogNo'];
			$pdf->writeHTMLCell(94, 20, 61, $y, $html, 0, 1, false, true, 'L', false);

			// item description
			$html = $this->_ellipsify($item['description'], 60);
			$pdf->writeHTMLCell(0, 20, 150, $y, $html, 0, 1, false, true, 'L', false);

			// SET FONT FOR DOLLAR AMOUNTS
			$pdf->SetFont('courier', '', 10);

			// item unit price
			$amount = $item['unitPrice'];
			$html = $this->_dollarify( $amount, 6 );
			$pdf->writeHTMLCell(80, 20, 430, $y, $html, 0, 1, false, true, 'R', false);

			// item total
			$amount = 1*$item['unitPrice'] * $item['quantity'];
			$html = $this->_dollarify( $amount, 6 );
			$pdf->writeHTMLCell(80, 20, 510, $y, $html, 0, 1, false, true, 'R', false);

			// RESET FONT FOR TEXT
			$pdf->setFont('helvetica', '', 8);
		}
		
		// Shipping and Handling
		$pdf->SetFont('courier', '', 10);
		$amount = $po['shippingAndHandling'];
		$html = $this->_dollarify( $amount, 6 );
		$pdf->writeHTMLCell(80, 20, 510, 566, $html, 0, 1, false, true, 'R', false);

		// Total Amount
		$pdf->setFont('courier', 'b', 10);
		$amount = $po['totalAmount'];
		$html = $this->_dollarify( $amount, 6 );
		$pdf->writeHTMLCell(80, 20, 510, 585, $html, 0, 1, false, true, 'R', false);

		// reset font to my default
		$pdf->setFont('helvetica', '', 8);

		// Remarks (& budgets breakdown if multiple)
		if ( $multipleBudgets )
		{
			$html = '<table border="0" cellpadding="0">';
			$html .= '<tr><td style="border-bottom:solid 1px #596dac;"><strong>Budget #</strong></td><td align="right" style="border-bottom:solid 1px #596dac;"><strong>Amount</strong></td></tr>';
			// $html = 'Budgets: <br>';
			// die ( print_r( $groupedItems, true ) );
			$toggle = true;
			foreach ( $groupedItems as $key => $value )
			{
				$bgColor = $toggle ? '#ffffff' : '#e0e4f3';
				$toggle = !$toggle;
				$html .= '<tr><td style="background-color:'.$bgColor.';">'.$key.'</td>';
				$total = 0;
				foreach ( $value as $item )
				{
					$total += 1*$item['unitPrice']*$item['quantity'];
				}
				if ( $key  == $po['shippingAndHandlingBudget'] )
				{
					$total += 1* $po['shippingAndHandling'];
				}
				$html .= '<td align="right" style="background-color:'.$bgColor.';">'.$this->_dollarify($total, 0).'</td></tr>';
			}
			$html .= '</table>';
			$pdf->writeHTMLCell(250, 20, 20, 640, $html, 0, 1, false, true, 'L', false);
		}

		if ( isset($po['remarks']) )
		{
			$y = $multipleBudgets ? 675 : 640;
			$html = $this->_ellipsify($po['remarks'], 150);
			$pdf->writeHTMLCell(270, 20, 20, $y, $html, 0, 1, false, true, 'L', false);
		}

		// Teacher
		$html = $po['teacher'];
		// $pdf->setFont('helvetica', '', 12 );
		$pdf->writeHTMLCell(270, 20, 315, 640, $html, 0, 1, false, true, 'L', false);

		// Divisional Director
		$html = $po['divDirector'];
		$pdf->writeHTMLCell(270, 20, 315, 678, $html, 0, 1, false, true, 'L', false);

		// Close and output the PDF document

		ob_end_clean();

		$pdf->Output($po['poNumber'].'.pdf', 'I');

	}

	protected function _ellipsify($s, $l )
	{
		$s = strlen($s) > $l ? substr( $s, 0, $l) . "&hellip;" : $s;
		return $s;
	}

	protected function _dollarify($number, $leftPad)
	{
		setlocale(LC_MONETARY, 'en_US.UTF-8');
		$format = '%#'.$leftPad.'n';
		$dollars = money_format($format, $number);
		$dollars = str_replace(' ', '&nbsp;', $dollars);
		return $dollars;
	}
}

/* End of file pdf.php */
/* Location: ./application/controllers/pdf.php */
