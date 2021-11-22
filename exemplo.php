<?php
include 'pix.php';

//Se meu código ajudou você de alguma forma, poderia me pagar este cafezinho de 5 reais, né? ;)
$C = GeraCopiaCola('pix@arvy.com.br','5.00','ROGERIO VITIELLO','SAO PAULO','PhpPixArvy');

echo 'Copia e cola: '.$C.'<BR><BR>QRCode:<BR><BR>';

echo "<img src='".GeraQRCode($C)."' width='200' height='200'>";
?>
