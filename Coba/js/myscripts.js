var status = "";

var Config={
    wsBroker : '192.168.56.1',
    wsPort : 19001,
	qos : 2,
	wsClientID : "",
	//wsClientID : "ClientID_" + parseInt(Math.random() * 100, 10),	
}

function autorun() {
	
	//doConnect();
	//subscribe();
	
}

function loadSettings() {
	Config.wsBroker = document.getElementById("broker").value;
	Config.wsPort = parseInt(document.getElementById("port").value);
	Config.qos = 2;
	Config.wsClientID = document.getElementById("clientID").value;
}

function showStatus(log) {
	var mylog = document.getElementById('logs');
	mylog.value += log + "\n";
	mylog.scrollTop = mylog.scrollHeight; //move to bottom
	//console.log(log);
}

//creates a new Messaging client
function doConnect() {
	loadSettings();
	client = new Paho.MQTT.Client(Config.wsBroker, Config.wsPort, Config.wsClientID);
	client.onConnectionLost = onConnectionLost; 
	client.onMessageArrived = onMessageArrived;
	client.connect(
		{
			timeout: 5,
			cleanSession : true, 
			onSuccess : onConnectSuccess, 
			onFailure : onFailure, 
			keepAliveInterval: 30,	
		}
		
	);
}

function disconnect() {
	client.disconnect();
	document.getElementById("status").value = "disconnect";
	alert("MQTT disconnected.");
	showStatus("MQTT disconnected.");
}

//subscribe to a topic
function subscribe(topic) {
	loadSettings();
	client.subscribe(topic, {qos: Config.qos});
	showStatus("Subscribed to topic " + topic);	
}

//unsubscribe from a topic
function unsubscribe(topic) {
	client.unsubscribe(topic);
	showStatus("Unsubscribe from topic " + topic);
}

//publish message to a topic
function publish(topic) {
	//loadSettings();	
	var msg = document.getElementById("msgSend").value;
	msg = Config.wsClientID + " , " + msg;
	message = new Paho.MQTT.Message(msg);
	message.destinationName = topic;
	message.qos = 2;
	message.retained = true;
	client.send(message);
	showStatus("Send : " + msg);
}

//called when connection successful
function onConnectSuccess(){
	var s = document.getElementById("status");
	s.value = "connected";
	var c = document.getElementById("clientID").value;
	document.getElementById("client").value = c;
	alert(Config.wsBroker + " connected.");
	showStatus(Config.wsBroker + " connected.");
}

//called when the connection is failed
function onFailure(message){
	alert("Connection failed: " + message.errorMessage);
}

//called when the client loses its connection
function onConnectionLost(responseObject) {
	if (responseObject.errorCode !== 0) {
		alert("onConnectionLost:" + responseObject.errorMessage);
	}
}

//called when a message arrives
var msgReceived = "";
function onMessageArrived(message) {
	msgReceived = message.payloadString;
	msgReceived = msgReceived.replace(/\n$/, ''); //remove new line	
	
	var myMsg = document.getElementById('msgReceived');
	myMsg.value += msgReceived + "\n";
	myMsg.scrollTop = myMsg.scrollHeight; //move to bottom
	
	//document.getElementById("msgReceived").value += msgReceived + "\n";
	if (countInstances(msgReceived) == 1) {
		var message_arr = extract_string(msgReceived); //split a string into an array		
		showStatus("Received from " + message_arr[0] + " value=" + message_arr[1]);	
		} else {	
		showStatus("Invalid payload");
	}	
}	

////////////////////////////////////////////////////
//split a string into an array of substrings
function extract_string(message_str) {
	var message_arr = message_str.split(","); //convert to array	
	return message_arr;
}	

//count number of delimiters in a string
var delimiter = ",";
function countInstances(message_str) {
	var substrings = message_str.split(delimiter);
	return substrings.length - 1;
}