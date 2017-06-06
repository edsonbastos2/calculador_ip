<?php
/*
Calculadora IP para LAN (Rede Privada) IPv4
Prof.: Luiz Fernando Albertin Bono Milan
Obs.: O aluno que ajudar a melhorar o código ganha 2 pontos na média 
(melhorar com base em meus critérios)
*/
require('classe/classe_calculadora_ip.php');

$_POST['ip'] = empty($_POST['ip']) ? null : $_POST['ip'];
$_POST['mascara'] = empty($_POST['mascara']) ? null : $_POST['mascara'];
$_POST['calcular'] = empty($_POST['calcular']) ? null : $_POST['calcular'];

$objCalculadoraIP = new CalculadoraIP;

if(!$_POST['calcular']){
	unset($objCalculadoraIP);	 
}else{

	$objCalculadoraIP->ip = $_POST['ip'];
	$objCalculadoraIP->mascara = $_POST['mascara'];
	$objCalculadoraIP->Calcular();

	$resultado = '';

	$resultado .= "\n\n";
	$resultado .= 'Mask    : '.$objCalculadoraIP->InserirPontosEmIps($objCalculadoraIP->mascara_binaria)."\n";
	$resultado .= 'IP      : '.$objCalculadoraIP->InserirPontosEmIps($objCalculadoraIP->ip_binario)."\n";
	$resultado .= 'IP Rede : '.$objCalculadoraIP->InserirPontosEmIps($objCalculadoraIP->endereco_de_rede)."\n\n";

	$resultado .= 'Mask Inv: '.$objCalculadoraIP->InserirPontosEmIps($objCalculadoraIP->mascara_inversa)."\n";
	$resultado .= 'IP      : '.$objCalculadoraIP->InserirPontosEmIps($objCalculadoraIP->ip_binario)."\n";
	$resultado .= 'IP Broad: '.$objCalculadoraIP->InserirPontosEmIps($objCalculadoraIP->endereco_de_broadcast)."\n\n";

	$resultado .= 'GTWs    : '.$objCalculadoraIP->InserirPontosEmIps($objCalculadoraIP->gateway1).' ou '.$objCalculadoraIP->InserirPontosEmIps($objCalculadoraIP->gateway2)."\n\n";

        $resultado .= 'Classe  : '.$objCalculadoraIP->classe."\n";
        $resultado .= 'M. Min. : '.$objCalculadoraIP->mascara_minima."\n";
        $resultado .= 'CIDR    : '.$objCalculadoraIP->cidr."\n";
	$resultado .= 'IP      : '.$objCalculadoraIP->IPBinarioParaDecimal($objCalculadoraIP->ip_binario)."\n";
	$resultado .= 'Mask    : '.$objCalculadoraIP->IPBinarioParaDecimal($objCalculadoraIP->mascara_binaria)."\n";
	$resultado .= 'IP Rede : '.$objCalculadoraIP->IPBinarioParaDecimal($objCalculadoraIP->endereco_de_rede)."\n";
	$resultado .= 'IP Broad: '.$objCalculadoraIP->IPBinarioParaDecimal($objCalculadoraIP->endereco_de_broadcast)."\n\n";

	$resultado .= 'GTWs    : '.$objCalculadoraIP->IPBinarioParaDecimal($objCalculadoraIP->gateway1).
			' ou '.
			$objCalculadoraIP->IPBinarioParaDecimal($objCalculadoraIP->gateway2)."\n\n";

	$resultado .= 'SubNets : '.$objCalculadoraIP->qtd_subnets_str.' ou '.$objCalculadoraIP->qtd_subnets."\n";
	$resultado .= 'IPs     : '.$objCalculadoraIP->qtd_ips_str.' ou '.$objCalculadoraIP->qtd_ips."\n";
	$resultado .= 'Hosts   : '.$objCalculadoraIP->qtd_hosts_str.' ou '.$objCalculadoraIP->qtd_hosts."\n\n";
	$msg = $objCalculadoraIP->msg;

	unset($objCalculadoraIP);
}

require('template/index_template.php');

?>
