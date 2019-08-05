<?php
	require_once './entidades/clases/archivos/FPDF/rotation.php';

class PDF extends PDF_Rotate
{
	function Header($text)
	{
		//Put the watermark
		$this->SetFont('Arial','B',50);
		$this->SetTextColor(255,192,203);
		$this->RotatedText(35,190,$txt,45);
	}

	function RotatedText($x, $y, $txt, $angle)
	{
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		$this->Rotate(0);
	}
}
?>
