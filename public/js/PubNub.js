/**
 * Created by Александр Пенко on 30.05.2017.
 */
pubnub = new PubNub({
    publishKey : 'pub-c-b9220392-f254-438f-b3b1-7d0bc16a5596',
    subscribeKey : 'sub-c-4e8b4b84-4492-11e7-b847-0619f8945a4f'
});

console.log("Subscribing..");
pubnub.subscribe({
    channels: ['parsing_status']
});