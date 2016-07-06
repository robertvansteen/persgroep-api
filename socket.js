const server = require('http').Server();
const io = require('socket.io')(server);
const Redis = require('ioredis');
const redis = new Redis();

redis.subscribe('events');
redis.on('message', (channel, message) => {
	const payload = JSON.parse(message);
	console.log('[New event]', payload.event);
	io.emit(payload.event, payload.data);
});

server.listen(3000, () => 'Socket connection is running on port 3000');
