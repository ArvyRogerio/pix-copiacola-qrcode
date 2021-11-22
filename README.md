# pix-copiacola-qrcode

Pix.php - Funções para gerar, de forma simples, a linha "Copia e Cola" e o QRCode

Necessita PHP 7 ou superior

Para o QRCode usa as bibliotecas do "chillerlan/php-qrcode", siga as instruções em https://github.com/chillerlan/php-qrcode e https://chillerlan.github.io/php-qrcode/

Na subpasta "qrcode" foram copiados do repositório acima (versão em 11/2021) e mantidos apenas os arquivos essenciais

Para a documentação do BC para o Pix, acesse: https://www.bcb.gov.br/content/estabilidadefinanceira/spb_docs/ManualBRCode.pdf

Função GeraCopiaCola(): recebe os campos e gera a linha usada no Copia e Cola e para gerar posteriormente o QRCode

Parâmetros obrigatórios:

- $Chave: chave Pix, podendo ser email, chave aleatória, celular (apenas números) ou cpf/cnpj (apenas números)
- $Valor: valor já no formato internacional, com ponto para decimais e sem separador de milhar, ex: 234.50
- $Beneficiario: nome do titular atrelado a chave Pix, até 25 posições e sem acentos
- $Cidade: cidade do titular atrelado a chave Pix, até 15 posições e sem acentos
- $Identificador: identificador até 25 posições, sem espaços e sem acentos, apenas letras e números

Função GeraQRCode(): recebe a linha gerada no Copia e Cola e transforma em QRCode, retornando a imagem em Base64 para dar "echo" direto no "src" da tag html "img"

Parâmetros obrigatórios:

 - $S: string gerada pela função GeraCopiaCola()

 Veja o arquivo "exemplo.php" para um exemplo funcional
 
 Desenvolvido por Rogério Vitiello - www.arvy.com.br 

-------------

**Se meu código ajudou você de alguma forma, poderia me pagar o cafezinho de 5 reais que está no exemplo.php, né? :)**

![qr](https://user-images.githubusercontent.com/11563884/142944206-dde81e3b-aa9a-4bce-b3fd-b211800a2b24.png)
