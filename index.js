const express = require("express");
var http = require("http"); //note
const app = express();
const port = process.env.PORT || 5000;
var server = http.createServer(function(req, res){
    var message = "It work realtime!\n",
        version = 'NodeJS ' + process.versions.node + '\n',
        response = [message, version].join('\n');
        res.end(response);
});



var io = require("socket.io")(server);

//middlewe
app.use(express.json());
var clients = {};

io.on("connection", (socket) => {
    console.log("connetetd");
    console.log(socket.id, "has joined");
    socket.on("signin",(id)=> {
        console.log(id);
        clients[id] = socket;
        // console.log(clients);
 
    });
    socket.on("message", (msg)=>{
        console.log(msg);
        let targetId = msg.idTake;
        if(clients[targetId])clients[targetId].emit("message",msg);
    });
});

server.listen(port, "0.0.0.0",()=> {
    console.log("server started");
});