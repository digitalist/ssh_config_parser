<?php

namespace SSHParser;

class SSHParser
{

    public static $keywords = array(
        "HostKeyword" => "Host",
        "MatchKeyword" => "Match",
        "AddressFamilyKeyword" => "AddressFamily",
        "BatchModeKeyword" => "BatchMode",
        "BindAddressKeyword" => "BindAddress",
        "CanonicalDomainsKeyword" => "CanonicalDomains",
        "CanonicalizeFallbackLocalKeyword" => "CanonicalizeFallbackLocal",
        "CanonicalizeHostnameKeyword" => "CanonicalizeHostname",
        "CanonicalizeMaxDotsKeyword" => "CanonicalizeMaxDots",
        "CanonicalizePermittedCNAMEsKeyword" => "CanonicalizePermittedCNAMEs",
        "ChallengeResponseAuthenticationKeyword" => "ChallengeResponseAuthentication",
        "CheckHostIPKeyword" => "CheckHostIP",
        "CipherKeyword" => "Cipher",
        "CiphersKeyword" => "Ciphers",
        "ClearAllForwardingsKeyword" => "ClearAllForwardings",
        "CompressionKeyword" => "Compression",
        "CompressionLevelKeyword" => "CompressionLevel",
        "ConnectionAttemptsKeyword" => "ConnectionAttempts",
        "ConnectTimeoutKeyword" => "ConnectTimeout",
        "ControlMasterKeyword" => "ControlMaster",
        "ControlPathKeyword" => "ControlPath",
        "ControlPersistKeyword" => "ControlPersist",
        "DynamicForwardKeyword" => "DynamicForward",
        "EnableSSHKeysignKeyword" => "EnableSSHKeysign",
        "EscapeCharKeyword" => "EscapeChar",
        "ExitOnForwardFailureKeyword" => "ExitOnForwardFailure",
        "FingerprintHashKeyword" => "FingerprintHash",
        "ForwardAgentKeyword" => "ForwardAgent",
        "ForwardX11Keyword" => "ForwardX11",
        "ForwardX11TimeoutKeyword" => "ForwardX11Timeout",
        "ForwardX11TrustedKeyword" => "ForwardX11Trusted",
        "GatewayPortsKeyword" => "GatewayPorts",
        "GlobalKnownHostsFileKeyword" => "GlobalKnownHostsFile",
        "GSSAPIAuthenticationKeyword" => "GSSAPIAuthentication",
        "GSSAPIDelegateCredentialsKeyword" => "GSSAPIDelegateCredentials",
        "HashKnownHostsKeyword" => "HashKnownHosts",
        "HostbasedAuthenticationKeyword" => "HostbasedAuthentication",
        "HostbasedKeyTypesKeyword" => "HostbasedKeyTypes",
        "HostKeyAlgorithmsKeyword" => "HostKeyAlgorithms",
        "HostKeyAliasKeyword" => "HostKeyAlias",
        "HostNameKeyword" => "HostName",
        "IdentitiesOnlyKeyword" => "IdentitiesOnly",
        "IdentityFileKeyword" => "IdentityFile",
        "IgnoreUnknownKeyword" => "IgnoreUnknown",
        "IPQoSKeyword" => "IPQoS",
        "KbdInteractiveAuthenticationKeyword" => "KbdInteractiveAuthentication",
        "KbdInteractiveDevicesKeyword" => "KbdInteractiveDevices",
        "KexAlgorithmsKeyword" => "KexAlgorithms",
        "LocalCommandKeyword" => "LocalCommand",
        "LocalForwardKeyword" => "LocalForward",
        "LogLevelKeyword" => "LogLevel",
        "MACsKeyword" => "MACs",
        "NoHostAuthenticationForLocalhostKeyword" => "NoHostAuthenticationForLocalhost",
        "NumberOfPasswordPromptsKeyword" => "NumberOfPasswordPrompts",
        "PasswordAuthenticationKeyword" => "PasswordAuthentication",
        "PermitLocalCommandKeyword" => "PermitLocalCommand",
        "PKCS11ProviderKeyword" => "PKCS11Provider",
        "PortKeyword" => "Port",
        "PreferredAuthenticationsKeyword" => "PreferredAuthentications",
        "ProtocolKeyword" => "Protocol",
        "ProxyCommandKeyword" => "ProxyCommand",
        "ProxyUseFdpassKeyword" => "ProxyUseFdpass",
        "PubkeyAuthenticationKeyword" => "PubkeyAuthentication",
        "RekeyLimitKeyword" => "RekeyLimit",
        "RemoteForwardKeyword" => "RemoteForward",
        "RequestTTYKeyword" => "RequestTTY",
        "RevokedHostKeysKeyword" => "RevokedHostKeys",
        "RhostsRSAAuthenticationKeyword" => "RhostsRSAAuthentication",
        "RSAAuthenticationKeyword" => "RSAAuthentication",
        "SendEnvKeyword" => "SendEnv",
        "ServerAliveCountMaxKeyword" => "ServerAliveCountMax",
        "ServerAliveIntervalKeyword" => "ServerAliveInterval",
        "StreamLocalBindMaskKeyword" => "StreamLocalBindMask",
        "StreamLocalBindUnlinkKeyword" => "StreamLocalBindUnlink",
        "StrictHostKeyCheckingKeyword" => "StrictHostKeyChecking",
        "TCPKeepAliveKeyword" => "TCPKeepAlive",
        "TunnelKeyword" => "Tunnel",
        "TunnelDeviceKeyword" => "TunnelDevice",
        "UpdateHostKeysKeyword" => "UpdateHostKeys",
        "UsePrivilegedPortKeyword" => "UsePrivilegedPort",
        "UserKeyword" => "User",
        "UserKnownHostsFileKeyword" => "UserKnownHostsFile",
        "VerifyHostKeyDNSKeyword" => "VerifyHostKeyDNS",
        "VisualHostKeyKeyword" => "VisualHostKey",
        "XAuthLocationKeyword" => "XAuthLocation",

	"FileHeader"                => "# ssh config generated by some php code",
	"GlobalConfigurationHeader" => "# global configuration",
	"HostConfigurationHeader"   => "# host-based configuration"
    );
    public static $metaKeywords = array("FileHeader", "GlobalConfigurationHeader", "HostConfigurationHeader");

    public $state;
    public $config;

    function __construct($rawContents = "")
    {
        $this->config = new Config($rawContents);

    }

    function newHost(array $hostnames, array $comments): Host
    {
        return new Host($hostnames, $comments);

    }

    /*
     * Go-версия передает reader и считывает содержимое файла внутри parse
     * мы передадим данные сразу.
     */

    function parse()
    {
        $lines = explode("\n", $this->config->getSource());
        $param = new Param();
        $host = null;
        $global = true;
        foreach ($lines as $k => &$v) {
            trim($v);
            if (!$v) {
                continue;
            }

            if (in_array($v, SSHParser::$metaKeywords)) {
                continue;
            }
            if ($v[0] == "#") {
                $param->comments[] = $v;
                continue;
            }

            $words = preg_split("/[\s]+/", trim($v));

            $param->setKeyword(array_shift($words));
            foreach ($words as $argIndex => $arg) {
                $param->addArg($arg);
            }
            if ($param->getKeyword() == SSHParser::$keywords['HostKeyword']) {
                $global = false;
                if ($host !== null) {
                    $this->config->hosts[] = $host;
                }
                $host = new Host($param->getComments(), $param->getArgs());
                $param = new Param();
                continue;
            } elseif ($global) {
                $this->config->globals[] = $param;
                $param = new Param();
                continue;
            }
            $host->addParam($param);
            $param = new Param();

        }
        if ($global){
            $this->config->globals[]=$param;
        }elseif ($host !== null){
            $this->config->hosts[]=$host;
        }

        return $this->config;

    }

    function writeTo($buf=""){
        $buf .= SSHParser::$keywords['FileHeader'];
        $buf .= "\n";
        $buf .= SSHParser::$keywords['GlobalConfigurationHeader'];

        foreach ($this->config->globals as $param){
            $buf .= $param."\n";
        }
        $buf .= "\n";
        $buf .= SSHParser::$keywords['HostConfigurationHeader'];

        foreach ($this->config->hosts as $host){
            $buf.=$host;
        }

        return $buf;
    }

    function writeToFile(string $path) {
        file_put_contents($path, $this->writeTo());
    }



}