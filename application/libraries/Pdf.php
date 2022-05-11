<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH."/third_party/dompdf/autoload.inc.php";
use Dompdf\Dompdf;

class Pdf
{

    public function generate($html, $filename = '', $stream = true, $attachment = 0, $paper = 'A4', $orientation = "portrait")
    {
        /*$options = new Options();*
        $options->set('isRemoteEnabled', true);*/
        $dompdf = new DOMPDF();
        $dompdf->set_option('isRemoteEnabled', TRUE);
        $dompdf->set_option('enable_remote', true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            // Attachment 1 para descargar el pdf
            $dompdf->stream($filename . ".pdf", array("Attachment" => $attachment));
        } else {
            return $dompdf->output();
        }
    }
}