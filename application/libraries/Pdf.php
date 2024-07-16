<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



require_once APPPATH . "/third_party/TCPDF-master/tcpdf.php";

class Pdf extends TCPDF {

    function __construct() {
        parent::__construct();
    }

    public function Header() {
        $pageN = $this->PageNo();
        $pageTot = $this->getAliasNbPages();
        $this->SetFont('pdfahelvetica', '', 10);
        $isi_header = '<br><br><br><table>'
                    . '<tr>'
                    . '     <td>';
                    if(isset($_SESSION["perusahaan"])) {
                        $isi_header .= $_SESSION["perusahaan"];  
                    }
            $isi_header .= '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '     <td>';
                    if(isset($_SESSION["alamat"])) {
                        $isi_header .= $_SESSION["alamat"];
                    }  
            $isi_header .= '</td>'
                    . '</tr>'
                    . '<tr>'
                    . '     <td>';
                    if(isset($_SESSION["phone"])) {
                        $isi_header .= 'Telp. '.$_SESSION["phone"];
                    }
                    if(isset($_SESSION["fax"])) {
                        $isi_header .= ' Fax '.$_SESSION["fax"];
                    }
            $isi_header .= '</td>'
                    . '</tr>'
                    . '</table>';
        if(isset($_SESSION["statuswatermark"])) {
            if($_SESSION["statuswatermark"] == 'Cancel'){
                $img_file = './assets/app/media/img/watermark/cancel.jpg';
                $this->Image($img_file, 0, 0, 223, 280, '', '', '', false, 300, '', false, false, 0);
            }else if($_SESSION["statuswatermark"] == 'Rejected'){
                $img_file = './assets/app/media/img/watermark/reject.jpg';
                $this->Image($img_file, 0, 0, 223, 280, '', '', '', false, 300, '', false, false, 0);
            }else if($_SESSION["statuswatermark"] == 'Waiting'){
                $img_file = './assets/app/media/img/watermark/waiting.jpg';
                $this->Image($img_file, 0, 0, 223, 280, '', '', '', false, 300, '', false, false, 0);
            }else if($_SESSION["statuswatermark"] == 'RejectedLandscape'){
                $img_file = './assets/app/media/img/watermark/rejectlandscape.jpg';
                $this->Image($img_file, 0, 0, 470, 280, '', '', '', false, 300, '', false, false, 0);
            }else if($_SESSION["statuswatermark"] == 'CancelLandscape'){
                $img_file = './assets/app/media/img/watermark/cancellandscape.jpg';
                $this->Image($img_file, 0, 0, 470, 280, '', '', '', false, 300, '', false, false, 0);
            }else if($_SESSION["statuswatermark"] == 'Paid'){
                $img_file = './assets/app/media/img/watermark/paid.jpg';
                $this->Image($img_file, 0, 0, 223, 280, '', '', '', false, 300, '', false, false, 0);
            }
        }
        $this->writeHTML($isi_header, true, false, true, false, '');
    }

}

/* End of file Pdf.php */

/* Location: ./application/libraries/Pdf.php */