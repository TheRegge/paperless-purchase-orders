<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
/**
* Creates the PO PDF
*/
require_once( APPPATH . 'libraries/tcpdf/tcpdf.php' );

class Pdfmaker extends TCPDF
{
	//Page header
	public function Header()
	{
		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;
		// Disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
		// Set background Image
		$dpi            = 150;
		$file_extension = 'png';
		$img_file       = APPPATH.'images/background-'.$dpi.'dpi.'.$file_extension;
		$img_x          = 0;
		$img_y          = 0;
		$img_w          = 612;
		$img_h          = 792;
		$img_type       = '';
		$img_link       = '';
		$img_align      = '';
		$img_resize     = false;
		$img_dpi        = $dpi;
		$img_palign     = '';
		$img_ismask     = false;
		$img_imgmask    = false;
		$img_border     = 0;
		$img_fitonpage  = false;

		$this->Image( $img_file, $img_x, $img_y, $img_w, $img_h, $img_type, $img_link, $img_align, $img_resize, $img_dpi, $img_palign, $img_ismask, $img_imgmask, $img_border, $img_fitonpage );
		// restore auto-page-break-status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// Set the starting point for the page content
		$this->setPageMark();
	}
} // end of class Pdfmaker