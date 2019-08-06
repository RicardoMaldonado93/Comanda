<?php
  ob_start();
  require_once './entidades/clases/archivos/FPDF/fpdf.php';
  require_once './entidades/clases/archivos/watermark.php';

  class PDF extends FPDF{

  public static function CrearPDF($cabecera, $datos, $cw, $hw, $name){
    $timestamp = time();
    $filename = 'exportar'. $timestamp . '.pdf';
    
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    
    $band=true;
    $flag =0;
    $pdf=new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(10,2,10);
    $pdf->setTitle($name);
    $pdf->SetFont('Arial','B',8);
    $pdf->setFillColor(81,192,215);

    //cargo las cabeceras
      for($i=0; $i<count($cabecera);$i++){

          if($i == count($cabecera) - 1)
              $flag = 1;

          if($i == 0) 
              $cellWidth = $cw /3;
          else
              $cellWidth = $cw;
          $pdf->Cell($cellWidth , 5, strtoupper($cabecera[$i]),1,$flag,'C',true);
          
      }

    $pdf->SetFont('Arial','I',6);
    $pdf->setFillColor(164,166,165);
    //cargo el contenido de la tabla
      for($i=0; $i<count($datos); $i++){
          for($j=0; $j<count($cabecera); $j++){
              
            if($j == 0) 
                $cellWidth = $cw /3;
            else
                $cellWidth = $cw;
              $pdf->Cell($cellWidth,5,$datos[$i]->{$cabecera[$j]},1,0,'C',$band);
              
          }
          $pdf->Ln();
          $band = !$band;
      }
      ob_end_clean();
      $pdf->SetDisplayMode('fullwidth');
      //$pdf->output('D',$filename);
      $pdf->output();
      exit();
  }
  
}
?>