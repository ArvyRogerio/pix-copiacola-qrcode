<?php

// Pix.php - Funções para gerar, de forma simples, a linha "Copia e Cola" e o QRCode
// Necessita PHP 7 ou superior
// Para o QRCode usa as bibliotecas do "chillerlan/php-qrcode", siga as instruções em https://github.com/chillerlan/php-qrcode e https://chillerlan.github.io/php-qrcode/
// Na subpasta "qrcode" foram copiados do repositório acima (versão em 11/2021) e mantidos apenas os arquivos essenciais
// Para a documentação do BC para o Pix, acesse: https://www.bcb.gov.br/content/estabilidadefinanceira/spb_docs/ManualBRCode.pdf
//
// Função GeraCopiaCola(): recebe os campos e gera a linha usada no Copia e Cola e para gerar posteriormente o QRCode
// Parâmetros obrigatórios:
// - $Chave: chave Pix, podendo ser email, chave aleatória, celular (apenas números) ou cpf/cnpj (apenas números)
// - $Valor: valor já no formato internacional, com ponto para decimais e sem separador de milhar, ex: 234.50
// - $Beneficiario: nome do titular atrelado a chave Pix, até 25 posições e sem acentos
// - $Cidade: cidade do titular atrelado a chave Pix, até 15 posições e sem acentos
// - $Identificador: identificador até 25 posições, sem espaços e sem acentos, apenas letras e números
//
// Função GeraQRCode(): recebe a linha gerada no Copia e Cola e transforma em QRCode, retornando a imagem em Base64 para dar "echo" direto no "src" da tag html "img"
// Parâmetros obrigatórios:
// - $S: string gerada pela função GeraCopiaCola()
//
// Veja o arquivo "exemplo.php" para um exemplo funcional

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

require_once(__DIR__.'/qrcode/autoload.php');

function GeraCopiaCola($Chave,$Valor,$Beneficiario,$Cidade,$Identificador) {

   $Valor=number_format($Valor,2,'.','');
   $Beneficiario=substr(trim($Beneficiario),0,25);
   $Cidade=substr(trim($Cidade),0,15);
   $Identificador=substr(trim($Identificador),0,25);

   $xChave='01'.sprintf('%02d',strlen($Chave)).$Chave;
   $xBCB='0014BR.GOV.BCB.PIX';

   $Saida ='000201';
   $Saida.='26'.sprintf('%02d',strlen($xBCB.$xChave)).$xBCB.$xChave;
   $Saida.='52040000'.'5303986';
   $Saida.='54'.sprintf('%02d',strlen($Valor)).$Valor;
   $Saida.='5802BR';
   $Saida.='59'.sprintf('%02d',strlen($Beneficiario)).$Beneficiario;
   $Saida.='60'.sprintf('%02d',strlen($Cidade)).$Cidade;
   $Saida.='62'.sprintf('%02d',strlen($Identificador)+4).'05'.sprintf('%02d',strlen($Identificador)).$Identificador;
   $Saida.='6304';

   return $Saida.CRC16($Saida);
   
}

function CRC16($str) {

   function charCodeAt($str,$i) {
      return ord(substr($str,$i,1));
   }

   $crc=0xFFFF;
   $strlen=strlen($str);

   for ($c=0;$c<$strlen;$c++) {
       $crc^=charCodeAt($str,$c)<<8;
       for ($i=0;$i<8;$i++) {
           if ($crc&0x8000) {
              $crc=($crc<<1)^0x1021;
           } else {
              $crc=$crc<<1;
           }
       }
   }

   $hex=$crc&0xFFFF;
   $hex=dechex($hex);
   $hex=strtoupper($hex);
   $hex=str_pad($hex,4,'0',STR_PAD_LEFT);

   return $hex;

}

function GeraQRCode($S) {
   $options=new QROptions(['eccLevel'=>QRCode::ECC_L,'outputType'=>QRCode::OUTPUT_MARKUP_SVG,'version'=>-1]);
   return (new QRCode($options))->render($S);
}

//Variação da versão original, com mais recursos: remove a "quiet zone" (área ao redor), e permite especificar a cor base (markup dark) em formato RGB.
//A função retorna um Array com 2 parâmetros: 1) o QRCode em base 64 (igual a original); 2) o tamanho em pixels do QRCode, o "tamanho mínimo".
//Use o segundo parâmetro como width/height e, para tamanhos maiores, use sempre um múltiplo dele, para melhor resultado visual.
//Exemplo:
//$QR=GeraQRCodeTamanho('codigo-copia-e-cola');
//echo '<IMG SRC="'.$QR[0].'" STYLE="width:'.($QR[1]*5).'px;height:'.($QR[1]*5).'px">';
//Assim você terá o QRCode com 5 vezes o tamanho mínimo, com a melhor qualidade visual possível.

function GeraQRCodeTamanho($S) {
   $qrcode=(new QRCode(new QROptions(['eccLevel'=>QRCode::ECC_L,'outputType'=>QRCode::OUTPUT_MARKUP_SVG,'addQuietzone'=>false,'markupDark'=>'#000','markupLight'=>'transparent','version'=>-1])));
   return array($qrcode->render($S),count($qrcode->getMatrix($S)->matrix()));
}
?>
