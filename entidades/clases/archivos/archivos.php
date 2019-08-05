<?php
    ob_start();
    require_once './entidades/clases/archivos/FPDF/fpdf.php';
    require_once './entidades/clases/archivos/FPDF/rotation.php';
    require_once './entidades/clases/conexion/AccesoDatos.php';

    class PDF extends FPDF{
   
  
	function RotatedText($x, $y, $txt, $angle)
	{
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		$this->Rotate(0);
	}
    public static function LoadData($cabecera, $datos)
    {
        
        $timestamp = time();
        $filename = 'AASD' . '.pdf';
        //var_dump($datos);
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        $pdf=new FPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',10);
        $pdf->SetMargins(6,1,0);

        for($i=0; $i<count($cabecera);$i++){

            $cellWidth = $pdf->GetStringWidth($cabecera[$i]);
            $pdf->Cell($cellWidth + 30, 5, strtoupper($cabecera[$i]),1,0,'C');
            
        }
        $pdf->Ln();
        $pdf->SetFont('Arial','I',8);
        $pdf->setFillColor(164,166,165);
        $band=true;
        for($i=0; $i<count($datos); $i++){
            
            for($j=0; $j<count($cabecera); $j++){
                $cellWidth = $pdf->GetStringWidth($cabecera[$j]);
                $pdf->Cell($cellWidth + 33,5,$datos[$i]->{$cabecera[$j]},1,0,'C',$band);
                
            }
            $pdf->Ln();
            $band = !$band;
        }
        ob_end_clean();
        $pdf->Output();
        exit();
    }
    
}
?>