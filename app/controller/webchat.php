<?php
error_reporting(E_ALL);
set_time_limit(0);
//ob_implicit_flush();

$address = '127.0.0.1';
$port = 10005;
//创建端口
if( ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
	echo "socket_create() failed :reason:" . socket_strerror(socket_last_error()) . "\n";
}

//绑定
if (socket_bind($sock, $address, $port) === false) {
	echo "socket_bind() failed :reason:" . socket_strerror(socket_last_error($sock)) . "\n";
}

//监听
if (socket_listen($sock, 5) === false) {
	echo "socket_bind() failed :reason:" . socket_strerror(socket_last_error($sock)) . "\n";
}

$isHandShake = 0;
do {
	//得到一个链接
	if (($msgsock = socket_accept($sock)) === false) {
		echo "socket_accepty() failed :reason:".socket_strerror(socket_last_error($sock)) . "\n";
		break;
	}
	// //welcome  发送到客户端
	// $msg = "server send:welcome";
	// socket_write($msgsock, $msg, strlen($msg));
	echo 'read client message\n';
	$buf = socket_read($msgsock, 8192);
	// $talkback = "received message:$buf\n";
	echo $buf;

			if(!$isHandShake){
					$Sec_WebSocket_Key = '';
		            if (preg_match("/Sec-WebSocket-Key: *(.*?)\r\n/i", $buf, $match)) {
		                $Sec_WebSocket_Key = $match[1];
		            } else {
		                $connection->send("HTTP/1.1 400 Bad Request\r\n\r\n<b>400 Bad Request</b><br>Sec-WebSocket-Key not found.<br>This is a WebSocket service and can not be accessed via HTTP.",
		                    true);
		                $connection->close();
		                return 0;
		            }
		            // Calculation websocket key.
		            $new_key = base64_encode(sha1($Sec_WebSocket_Key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
		            // Handshake response data.
		            $handshake_message = "HTTP/1.1 101 Switching Protocols\r\n";
		            $handshake_message .= "Upgrade: websocket\r\n";
		            $handshake_message .= "Sec-WebSocket-Version: 13\r\n";
		            $handshake_message .= "Connection: Upgrade\r\n";
		            $handshake_message .= "Server: workerman/13"."\r\n";
		            $handshake_message .= "Sec-WebSocket-Accept: " . $new_key . "\r\n\r\n";
		            $outData = $handshake_message;
			}else{
				$outData = 'hello';
			}
 			


	if (false === socket_write($msgsock, $outData, strlen($outData))) {
		echo "socket_write() failed reason:" . socket_strerror(socket_last_error($sock)) ."\n";
	} else {
		echo 'send success';
	}
	// sleep(3000);
	
	socket_close($msgsock);
} while(true);
//关闭socket
socket_close($sock);


?>