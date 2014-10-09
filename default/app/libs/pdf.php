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
        $this->Image("img/logo1.jpg" , 5 ,5, 57 , 22 , "JPG");
        $this->SetFont('Arial','B',10);
        $this->Text(80,13,utf8_decode('República Bolivariana de Venezuela'));
        $this->Text(84,17,utf8_decode('Gobernación del Estado Falcón'));
        $this->Text(68,21,utf8_decode('Instituto Autónomo de Aeropuertos del Estado Falcón'));
        $this->Text(78,25,utf8_decode('Departamento de Recaudación de IAEF'));
        $this->Image("img/logo_iaef_2.jpg" , 160, 10, 45, 25, "JPG");

        if (is_null($this->fecha))
            $this->fecha = date('d/m/Y');
        if (is_null($this->hora))
            $this->hora = date('H:i:s');
    }

    public function Footer(){  // Número de Página
        $this->SetFont('Arial','',8);
        $this->Text(100,270, 'Pagina '. $this->PageNo() . '/{nb}');
    }

    public function HeaderCierre() {
        // Titulo del Formato
        $this->SetFont('Arial','B',16);
        $this->Text(90,35,'Cierre de Caja');
        // ----> Titulos de Datos
        $this->SetFont('Arial','B',10);
        $this->Text(10,44,'Recaudador:');
        $this->Text(10,48,'Fecha:');
        #$this->Text(10,52,'Hora:');

        // ----> Informacion Variable
        $this->SetFont('Arial','',10);
        $this->Text(33,44,$this->recaudador);
        $this->Text(33,48,$this->fecha);
        #$this->Text(33,52,$this->hora);

        // CELDA ENCABEZADO
        $this->Line(200,58.5,9.5,58.5);
        $this->Line(200,58.8,9.5,58.8);

        // ----> Texto Encabezado
        $this->SetFont('Arial','B',10);
        $this->Text(15,63,'ID');
        $this->Text(35,63,'FACTURAS');
        $this->Text(80,63,'Clientes');
        $this->Text(155,63,'PAGO');
        $this->Text(175,63,'MONTO');

        $this->Line(200,65.5,9.5,65.5);
        $this->Line(200,65.8,9.5,65.8);
    }


    public function HeaderCuentas()
    {
       // Titulo del Formato
       $this->SetFont('Arial','B',16);
       $this->Text(86,35,utf8_decode('Cuentas por Cobrar'));
       $this->SetFont('Arial','B',12);
       $this->Text(78,40,'DESDE 16/03/14 - HASTA 31/03/14');
       $this->SetFont('Arial','B',10);
       $this->Text(10,44,utf8_decode('Aerolínea:'));
       $this->SetFont('Arial','',10);
       $this->Text(28,44,utf8_decode('AEROTUY aerolinea creada en venezuela'));

       // CELDA ENCABEZADO
       $this->SetFont('Arial','B',12);
       $this->SetFillColor(255,140,000);
       $this->SetXY(10, 45);
       $this->Cell(65, 6,'FECHA', 1, 0, 'C', 1);
       $this->SetXY(75, 45);
       $this->Cell(65, 6,'NRO FACTURA', 1, 0, 'C', 1);
       $this->SetFillColor(255,165,000);
       $this->SetXY(140, 45);
       $this->Cell(65, 6,'MONTO BSF', 1, 0, 'C', 1);

    }

    public function HeaderCredito(){
        // Titulo del Formato
        $this->SetFont('Arial','B',16);
        $this->Text(86,35,utf8_decode('Cuentas por Cobrar'));
        $this->SetFont('Arial','B',12);
        $this->Text(78,40, $this->periodo);
        $this->SetFont('Arial','B',10);
        $this->Text(10,44,utf8_decode('Cliente :'));
        $this->SetFont('Arial','',10);
        $this->Text(28,44, $this->cliente);
    }

    public function HeaderCreditoFBO(){
        // Titulo del Formato
        $this->SetFont('Arial','B',16);
        $this->Text(86,35,utf8_decode('Cuentas por Cobrar'));
        $this->SetFont('Arial','B',12);
        $this->Text(78,40, $this->periodo);
        $this->SetFont('Arial','B',10);
        $this->Text(10,44,utf8_decode('Prestador de Servicio :'));
        $this->SetFont('Arial','',10);
        $this->Text(50,44, $this->cliente);
    }

    public function HeaderFactura($prefijo, $factura, $tipo_rif, $rif, $cliente, $telefono, $direccion, $recaudador, $fecha, $hora) {
        // Titulo del Formato
        $this->SetFont('Arial','B',10);
        $this->Text(130,35,'Nro de Factura :      '.$prefijo.'-'.$factura);
        $this->Text(155,39,utf8_decode('(COPIA)'));

        // ----> INFORMACION DE FACTURA <---- //
        // Titulos Columna Izquierda
        $this->SetFont('Arial','B',10);
        $this->Text(10,44,'RIF:');
        $this->Text(10,48,utf8_decode('Razón Social:'));
        $this->Text(10,52,utf8_decode('Telefóno:'));
        $this->Text(10,56,utf8_decode('Dirección:'));

        // Titulos Columna Derecha
        $this->Text(130,44,'Recaudador:');
        $this->Text(130,48,'Fecha:');
        $this->Text(130,52,'Hora:');


        //>>>>>>>> Informacion Columna Izquierda <<<<<<//
        // Columna Izquierda
        $this->SetFont('Arial','',10);
        $this->Text(34,44,$tipo_rif.'-'.$rif); // RIF
        $this->Text(34,48,utf8_decode($cliente)); // Razon Social
        $this->Text(34,52,$telefono); // Telefono
        $this->Text(10,60,utf8_decode($direccion)); // Direccion Fiscal

        // Columna Derecha
        $this->Text(155,44,utf8_decode($recaudador)); // Recaudador
        $this->Text(155,48,$fecha); // Fecha
        $this->Text(155,52,$hora); // Fecha

    }

    public function HeaderIngreso()
    {

        // Titulo del Formato
        $this->SetFont('Arial','B',16);
        $this->Text(139,35,utf8_decode('Relación de Ingresos por Rubros'));
        $this->SetFont('Arial','B',14);
        $this->Text(145,43,'DESDE 16/03/14 - HASTA 31/03/14');

        // CELDA ENCABEZADO
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(255,140,000);
        $this->SetXY(10, 45);
        $this->Cell(20, 6,'FECHA', 1, 0, 'C', 1);

        $this->SetFillColor(255,165,000);
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

    public function SetDosa($ruta, $aerolinea, $piloto, $matricula, $peso, $capacidad, $pasajeros, $llegada, $salida){
        $inicio = $this->line;
        $this->NewLine();
        $this->SetFont('Arial','B',10);
        $this->Text(10,$this->line,utf8_decode('Vuelo:'));
        $this->NewLine(2);
        $this->Text(10,$this->line,utf8_decode('Aerolínea:'));
        $this->NewLine(2);
        $this->Text(10,$this->line,utf8_decode('Aeronave:'));
        $this->NewLine(2);
        $this->Text(10,$this->line,utf8_decode('Peso:'));
        $this->NewLine(2);
        $this->Text(10,$this->line,utf8_decode('Llegada:'));

        $this->SetLine($inicio);
        $this->NewLine(3);
        $this->Text(130,$this->line,utf8_decode('Cant. de Pasajeros:'));
        $this->NewLine(2);
        $this->Text(130,$this->line,utf8_decode('Piloto:'));
        $this->NewLine(2);
        $this->Text(130,$this->line,utf8_decode('Capacidad:'));
        $this->NewLine(2);
        $this->Text(130,$this->line,utf8_decode('Salida:'));

        $this->SetLine($inicio);
        $this->NewLine(2);
        $this->SetFont('Arial','',10);
        $this->Text(10,$this->line,utf8_decode($ruta)); // Vuelo
        $this->NewLine(2);
        $this->Text(10,$this->line,utf8_decode($aerolinea)); // Aerolinea
        $this->NewLine(2);
        $this->Text(10,$this->line, str_replace('-', '', $matricula)); // Aeronave
        $this->NewLine(2);
        $this->Text(10,$this->line,$peso.' Kgs.'); // Peso
        $this->NewLine(2);
        $this->Text(10,$this->line,$llegada); // Peso

        $this->SetLine($inicio);
        $this->NewLine(4);
        $this->Text(130,$this->line, $pasajeros); // Cantidad de Pasajeros
        $this->NewLine(2);
        $this->Text(130,$this->line,utf8_decode($piloto)); // Piloto
        $this->NewLine(2);
        $this->Text(130,$this->line, $capacidad); // Capacidad
        $this->NewLine(2);
        $this->Text(130,$this->line, $salida); // Salida
    }

    public function SetPrestador($prestador){
        $this->NewLine();
        $this->SetFont('Arial','B',10);
        $this->Text(10,$this->line, utf8_decode('Prestador de Servicio:'));
        $this->NewLine();
        $this->SetFont('Arial','',10);
        $this->Text(10,$this->line, utf8_decode($prestador)); // Prestador de Servicio
    }

    public function SetProductos() {
        // CELDA ENCABEZADO
        $this->NewLine(2);
        $this->SetFont('Arial','B',10);
        $this->Text(15,$this->line,'ID');
        $this->Text(35,$this->line,utf8_decode('Descripción'));
        $this->Text(145,$this->line,'Precio');
        $this->Text(165,$this->line,'Cant.');
        $this->Text(180,$this->line,'Sub-Total');

        $superior = $this->line-5;
        $inferior = $this->line+1;
        $this->Line(200, $superior+.5, 9.5, $superior+.5);
        $this->Line(200, $superior+.8, 9.5, $superior+.8);
        $this->Line(200, $inferior+.5, 9.5, $inferior+.5);
        $this->Line(200, $inferior+.7, 9.5, $inferior+.7);
    }

    public function SetCliente($cliente) {
        $this->cliente = utf8_decode($cliente);
    }

    public function SetPeriodo($inicio, $final){
        $this->periodo = 'DESDE '.$inicio.' - HASTA '.$final;

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

}

?>