<?php
/*
Calculadora IP para LAN (Rede Privada) IPv4
Prof.: Luiz Fernando Albertin Bono Milan
Obs.: O aluno que ajudar a melhorar o código ganha 2 pontos na média
(melhorar com base em meus critérios)
*/
class CalculadoraIP {

	private $ip;
	private $ip_binario;
	private $cidr;
	private $classe;
	private $mascara;
	private $mascara_minima;
	private $mascara_binaria;
	private $mascara_inversa;
	private $endereco_de_rede;
	private $endereco_de_broadcast;
	private $gateway1;
	private $gateway2;
	private $octetos = array();
	private $octetos_mascara = array();
	private $qtd_subnets;
	private $qtd_ips;
	private $qtd_hosts;
	private $qtd_subnets_str;
	private $qtd_ips_str;
	private $qtd_hosts_str;
	private $msg;
	private $info;

	public function __construct(){
		$this->info = 	'CalculadoraIP->ip deve ser decimal em 4 octetos separados por ponto "."'.
				"\n".
				'CalculadoraIP->mascara deve ser na nota&ccedil;&atilde;o 
				CIDR ou decimal em 4 octetos separados por ponto "."';
	}

	public function __set($atributo, $valor){
		switch($atributo){
			case 'ip_binario':
			case 'cidr':
			case 'classe':
			case 'mascara_minima':
			case 'mascara_binaria':
			case 'mascara_inversa':
			case 'octetos':
			case 'octetos_mascara':
			case 'endereco_de_rede':
			case 'endereco_de_broadcast':
			case 'gateway1':
			case 'gateway2':
			case 'qtd_subnets':
			case 'qtd_ips':
			case 'qtd_hosts':
			case 'qtd_subnets_str':
			case 'qtd_ips_str':
			case 'qtd_hosts_str':
				return false;
		}
		$this->$atributo = $valor;
		return true;
	}

	public function __get($atributo){
		return $this->$atributo;
	}

	public function Calcular(){
		if(empty($this->ip)){
			echo 	'Utilize CalculadoraIP->ip = IP; antes de chamar 
				o m&eacute;todo CalculadoraIP->Calcular();';
			return false;
		}
		if(empty($this->mascara)){
			echo 	'Utilize CalculadoraIP->mascara = M&Aacute;SCARA; 
				antes de chamar o m&eacute;todo CalculadoraIP->Calcular();';
			return false;
		}
		$this->VerificarIP();
		$this->ConverterMascaraParaBinario();
		$this->OperacaoBitaBit();
		$this->CalcularGateways();
		$this->CalcularQtdSubNets();
		$this->CalcularQtdIPs();
		$this->CalcularQtdHosts();			
	}

	private function VerificarIP(){

		$this->octetos = explode('.',$this->ip);

		if(count($this->octetos) == 4){
			if($this->octetos[0] == 192 && $this->octetos[1] == 168){//classe C
				$this->classe = 'C';
				$this->mascara_minima = 16;
			}elseif($this->octetos[0] == 172 && ($this->octetos[1] >= 16 || $this->octetos[1] <= 31)){//classe B
				$this->classe = 'B';
				$this->mascara_minima = 12;
			}elseif($this->octetos[0] == 10){//classe A
				$this->classe = 'A';
				$this->mascara_minima = 8;
			}elseif($this->octetos[0] == 169 && $this->octetos[1] == 254){//APIPA
				$this->classe = 'APIPA';
				$this->mascara_minima = 16;
			}else{
				$this->msg = 'O IP '.$this->ip.' n&atilde;o &eacute; um IP privado';	
				return false;
			}
		}else{
			$this->msg = 'IP inv&aacute;lido';
			return false;
		}
		$this->ip_binario = '';
		foreach($this->octetos as $octeto){
			$this->ip_binario .= substr('00000000'.decbin($octeto),-8);					
		}
	}

	private function ConverterMascaraParaBinario(){
		if($this->mascara >= 0 && $this->mascara <= 32){//Se já estiver na notação CIDR, return true
			$this->cidr = $this->mascara;
			$this->mascara_binaria = '';
			for($i=0 ; $i < $this->mascara ; $i++){
				$this->mascara_binaria .= '1';
			}
			$this->mascara_binaria = substr($this->mascara_binaria.'00000000000000000000000000000000',0,32);
		}else{
			$this->octetos_mascara = explode('.',$this->mascara);

			if(count($this->octetos_mascara) == 4){
				$this->mascara_binaria = '';
				foreach($this->octetos_mascara as $octeto){
					$this->mascara_binaria .= substr('00000000'.decbin($octeto),-8);					
				}
				$this->cidr = 0;
				for($i=0 ; $i < 32 ; $i++){
					if(substr($this->mascara_binaria,$i,1) == '1'){
						$this->cidr++; 
					}
				}				
			}else{
				$this->msg = 'M&aacute;scara inv&aacute;lida';	
				return false;
			}
		}
		$flg_fim_rede = false;
		for($i=0 ; $i < 32 ; $i++){//Verifica se é uma máscara válida
			if($flg_fim_rede && substr($this->mascara_binaria, $i, 1) == 1){
				$this->msg = 'M&aacute;scara inv&aacute;lida';
				return false;
			}
			if(substr($this->mascara_binaria, $i, 1) == 0){
				$mascara = $i;
				$flg_fim_rede = true;
			}
		}
		if($this->mascara_minima > $mascara){
			$this->msg = 'M&aacute;scara inferior a m&iacute;nima para classe: '.$this->classe;
			return false;
		}
	}

	private function OperacaoBitaBit(){
		$this->endereco_de_rede = '';
		//Simulassão da operação bit a bit com operador AND para achar o IP de rede
		for($i=0 ; $i < 32 ; $i++){
			if(substr($this->mascara_binaria,$i,1) && substr($this->ip_binario,$i,1)){
				$this->endereco_de_rede .= '1';
			}else{
				$this->endereco_de_rede .= '0';
			}
		}
		//operação bit a bit com o operador NOT na máscara para achar o IP de broadcast
		$this->mascara_inversa = '';
		for($i=0 ; $i < 32 ; $i++){
			//Simulassão da operação bit a bit com operador OR para achar a máscara inversa
			if(substr($this->mascara_binaria,$i,1) == '1'){
				$this->mascara_inversa .= '0';
			}else{
				$this->mascara_inversa .= '1';
			}
		}
		//operação bit a bit com o operador OR com a máscara inversa para achar o IP de broadcast
		$this->endereco_de_broadcast = '';
		for($i=0 ; $i < 32 ; $i++){
			if(substr($this->mascara_inversa,$i,1) || substr($this->ip_binario,$i,1)){
				$this->endereco_de_broadcast .= '1';
			}else{
				$this->endereco_de_broadcast .= '0';
			}
		}				 
	}

	private function CalcularGateways(){//Obs.: Este método precisa ser melhorado
		$subtracao = decbin(
			bindec($this->endereco_de_broadcast)-bindec($this->endereco_de_rede)
		);
		if($subtracao <= 1){
			$this->msg = 'N&atilde;o h&aacute; IP(s) v&aacute;lido(s) nesta rede';
			$this->gateway1 = null;
			$this->gateway2 = null;
		}else{
			$soma = substr('00000000'.decbin(bindec(substr($this->endereco_de_rede,-8))+1),-8);
			$this->gateway1 = substr($this->endereco_de_rede,0,24).$soma;
			$subtracao = substr('00000000'.decbin(bindec(substr($this->endereco_de_broadcast,-8))-1),-8);
			$this->gateway2 = substr($this->endereco_de_broadcast,0,24).$subtracao;
		}
	}

	private function CalcularQtdSubNets(){
		$this->qtd_subnets = pow(2,($this->cidr - $this->mascara_minima));
		$this->qtd_subnets_str = '2^'.($this->cidr - $this->mascara_minima);
	}

	private function CalcularQtdIPs(){
		$this->qtd_ips = pow(2,(32 - $this->cidr));
		$this->qtd_ips_str = '2^'.(32 - $this->cidr);
	}

	private function CalcularQtdHosts(){
		$this->qtd_hosts = pow(2,(32 - $this->cidr))-2;
		$this->qtd_hosts_str = '2^'.(32 - $this->cidr).'-2';
	}

	public function IPBinarioParaDecimal($ip_binario){
		return 	bindec(substr($ip_binario,0,8)).'.'.
			bindec(substr($ip_binario,8,8)).'.'.
			bindec(substr($ip_binario,16,8)).'.'.
			bindec(substr($ip_binario,24,8));
	}

	public function MascaraBinariaParaDecimal($mascara_binaria){
		return $this->IPBinarioParaDecimal($mascara_binaria);
	}
	public function InserirPontosEmIps($binario){
		return 	substr($binario,0,8).'.'.
			substr($binario,8,8).'.'.
			substr($binario,16,8).'.'.
			substr($binario,24,8);
	}
	public function __destruct(){
		echo '<center>Calcularora IP (IPv4) para LAN (IPs privados) - 
		Edson Bastos</center>';
	}
}
?>
