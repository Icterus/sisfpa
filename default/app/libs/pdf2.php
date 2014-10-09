<?php

/**
* Clase Hija de FPDF especifica para los reportes del Sistema
*/
Load::lib('fpdf/fpdf');

class PDF extends FPDF
{
    private $line = 60;
    private $size = 4;
    private $recaudador = NULL;
    private $fecha = NULL;
    private $hora = NULL;

    public function Header(){
        // Membrete del Reporte
        //$this->Image("img/logo_iaef_2.jpg" , 160, 10, 45, 25, "JPG");

        $this->Image("img/logo1.jpg" , 9 ,5, 57 , 22 , "JPG");
        $this->SetFont('Arial','B',10);
        $this->Text(152,13,utf8_decode('República Bolivariana de Venezuela'));
        $this->Text(156,17,utf8_decode('Gobernación del Estado Falcón'));
        $this->Text(139,21,utf8_decode('Instituto Autónomo de Aeropuertos del Estado Falcón'));
        $this->Text(150,25,utf8_decode('Departamento de Recaudación de IAEF'));
        $this->Image("img/logo_iaef_2.jpg", 283, 5, 60, 25, "JPG");


        if (is_null($this->fecha))
            $this->fecha = date('d/m/Y');
        if (is_null($this->hora))
            $this->hora = date('H:i:s');
    }

    public function Footer(){  // Número de Página
        $this->SetFont('Arial','',8);
        $this->Text(170,200, 'Pagina '. $this->PageNo() . '/{nb}');
    }

    public function HeaderIngreso()
    {

        // Titulo del Formato
        $this->SetFont('Arial','B',16);
        $this->Text(139,35,utf8_decode('Relación de Ingresos por Rubros'));
        $this->SetFont('Arial','B',14);
        $this->Text(138,43,$this->periodo);

        // CELDA ENCABEZADO
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(255,165,000);
        $this->SetXY(10, 45);
        $this->Cell(20, 6,'FECHA', 1, 0, 'C', 1);
        $this->SetXY(30, 45);
        $this->Cell(20, 6,'Tasas', 1, 0, 'C', 1);
        $this->SetXY(50, 45);
        $this->Cell(25, 6,'Aterrizaje', 1, 0, 'C', 1);
        $this->SetXY(75, 45);
        $this->Cell(25, 6,'Carga/Acopio', 1, 0, 'C', 1);
        $this->SetXY(100, 45);
        $this->Cell(25, 6,'Combustible', 1, 0, 'C', 1);
        $this->SetXY(125, 45);
        $this->Cell(25, 6,'Carnet', 1, 0, 'C', 1);
        $this->SetXY(150, 45);
        $this->Cell(28, 6,'Arrendamiento', 1, 0, 'C', 1);
        $this->SetXY(178, 45);
        $this->Cell(26, 6,'Pase Vehiculo', 1, 0, 'C', 1);
        $this->SetXY(204, 45);
        $this->Cell(32, 6,utf8_decode('Atención x Vuelo'), 1, 0, 'C', 1);
        $this->SetXY(236, 45);
        $this->Cell(26, 6,'30% PLT-E01', 1, 0, 'C', 1);
        $this->SetXY(262, 45);
        $this->Cell(24, 6,'70%PLT-E01', 1, 0, 'C', 1);
        $this->SetXY(286, 45);
        $this->Cell(20, 6,'Otros', 1, 0, 'C', 1);
        $this->SetXY(306, 45);
        $this->Cell(20, 6,'Subtotal', 1, 0, 'C', 1);
        $this->SetXY(326, 45);
        $this->Cell(20, 6,'TOTAL', 1, 0, 'C', 1);

    }

    function HeaderConsolidado(){ //función header

        // Titulo del Formato
        $this->SetFont('Arial','B',16);
        $this->Text(149,35,utf8_decode('Consolidado de Ingresos'));
        $this->SetFont('Arial','B',14);
        $this->Text(165,43, $this->mes);

        // CELDA ENCABEZADO
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(255,165,000);
        $this->SetXY(10, 45);
        $this->Cell(20, 6,'FECHA', 1, 0, 'C', 1);
        $this->SetXY(30, 45);
        $this->Cell(20, 6,'Tasas', 1, 0, 'C', 1);
        $this->SetXY(50, 45);
        $this->Cell(25, 6,'Aterrizaje', 1, 0, 'C', 1);
        $this->SetXY(75, 45);
        $this->Cell(25, 6,'Carga/Acopio', 1, 0, 'C', 1);
        $this->SetXY(100, 45);
        $this->Cell(25, 6,'Combustible', 1, 0, 'C', 1);
        $this->SetXY(125, 45);
        $this->Cell(25, 6,'Carnet', 1, 0, 'C', 1);
        $this->SetXY(150, 45);
        $this->Cell(28, 6,'Arrendamiento', 1, 0, 'C', 1);
        $this->SetXY(178, 45);
        $this->Cell(26, 6,'Pase Vehiculo', 1, 0, 'C', 1);
        $this->SetXY(204, 45);
        $this->Cell(32, 6,utf8_decode('Atención x Vuelo'), 1, 0, 'C', 1);
        $this->SetXY(236, 45);
        $this->Cell(26, 6,'30% PLT-E01', 1, 0, 'C', 1);
        $this->SetXY(262, 45);
        $this->Cell(24, 6,'70%PLT-E01', 1, 0, 'C', 1);
        $this->SetXY(286, 45);
        $this->Cell(20, 6,'Otros', 1, 0, 'C', 1);
        $this->SetXY(306, 45);
        $this->Cell(20, 6,'Subtotal', 1, 0, 'C', 1);
        $this->SetXY(326, 45);
        $this->Cell(20, 6,'TOTAL', 1, 0, 'C', 1);
    }

    public function SetCliente($cliente) {
        $this->cliente = utf8_decode($cliente);
    }

    public function SetPeriodo($inicio, $final){
        $this->periodo = 'DESDE '.$inicio.' - HASTA '.$final;
    }

    public function SetMes($mes, $ano){
        $meses = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
            );
        $this->mes = $meses[$mes].' '.$ano;
        print $this->mes;
    }

    public function SetRecaudador($nombre)
    {
        $this->recaudador = $nombre;
    }

    public function SetFecha($fecha=NULL)
    {
        $this->fecha = $fecha;
    }

    public function SetHora($hora=NULL)
    {
        $this->hora = $hora;
    }

    public function NewLine($step=1){
        $this->line += ($this->size*$step);
        return $this->line;
    }

    public function SetLine($line) {
        $this->line = $line;
    }

    public function contable($number){
        // Python
        /* def contable(numero):
        numero = str(numero).replace(' '+MONEDA, '')  # Por seguridad elimino el tipo de moneda
        e, d = str(Decimal(numero)).split('.')
        m = '{:,}'.format(int(e))
        d = str(d).replace('0.', '')
        return str(m).replace(',', '.') + ',' + d[:2] + ' ' + MONEDA */
        // return number_format($number, 2, ',', '.').' Bs.';
        $punto = strpos($number, '.');
        if ($punto !== false) {
            list($e, $d) = explode('.', $number);
            $entero = number_format($e, 0, ',', '.');
            $decimal = (strlen($d) == 1)?$d.'0':$d;
            $number = $entero.','.substr($decimal, 0, 2).' Bs.';
        } else{
            $entero = number_format($number, 0, ',', '.');
            $decimal = '00';
            $number = $entero.','.$decimal.' Bs.';
        }
        return $number;
    }

    public function legible($number){
        return number_format($number, 2, ',', '.');
    }

}

?>