<?php
    include(dirname(__FILE__) . '/basic_auth.php');
?>
<html>

<head>
    <!-- development version, includes helpful console warnings -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha1/0.6.0/sha1.min.js" integrity="sha512-q6FuE4ifzTygTD/ug8CFnAFXl+i1zXqBWP6flRAuSWjaXrFu4Cznk8Xr+VrWMyi7fSatbssh7ufobAetvXK8Pg==" crossorigin="anonymous"></script>
</head>

<body>
    <div id="app">
        <v-app>
            <v-main>
                <v-container>

                    <h1>WebHook Configure</h1>
                    <h3>Key storage management of webhook.php for dynamic testing</h3>
                    <v-textarea
                        name="input-7-1"
                        label="How to use this for testing."
                        value="That situation is like in below.
1. Merchant got the credential and registered new public key which they created.
2. But Merchant have no web-hook created in their server, but they want to keep to test as successfully.
( It means our provided web-hook should works with new public key which they registered at business-cms )
3. Our Engineer team register new public-key/private-key pair from provided by Merchant.
then these key pair regist at our config site of webhook.
( After this, our webhook will works with new registered private/public key pairs , and Merchant should call 'registerPayment' api with custom web-hook address. )
- (Old) webhookUrl : http://testshop.anyonepay.ph/test/webhook.php?v=verifyOrCompletion&call=SANDBOX
- (New) webhookUrl : http://testshop.anyonepay.ph/test/webhook.php?v=verifyOrCompletion&call=SANDBOX&pk=[SHA1 of merchant's new public key]"
                        hint="Hint text"
                    ></v-textarea>
                    <h4>2020.10.21 updated</h4>
                    <br/>
                    <h4>List of webhookKeys</h4>
                    <ul id="example-1" style="background-color: #DDD;padding:10px; border:1px solid #333;">
                        <li v-for="key in webhookKeys" :key="key.publicKey">
                            <p>
                                <span>Created : </span><span>{{ key.created }}</span>
                                <span>
                                    <v-btn v-on:click="deleteKey(key)" color="primary">Delete</v-btn>
                                </span>
                            </p>
                            <p><span>SHA1 : </span><span>{{ key.sha1 }}</span></p>
                            <p><span>Public Key : </span><span>{{ key.publicKey }}</span></p>
                            <p><span>Private Key : </span><span>{{ key.privateKey }}</span></p>
                        </li>
                    </ul>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <p>New Key here</p>
                            <v-text-field label="publicKey" v-model="newPublicKey"></v-text-field>
                            <v-text-field label="privateKey" v-model="newPrivateKey"></v-text-field>
                        </v-col>
                        <v-btn v-on:click="addNewKey()" color="primary">Add New Key</v-btn>
                        <span>
                            You can generate this here : 
                            <a href="https://anyonepay.readme.io/reference#create-key" target="_blank">Click.</a>
                        </span>
                    </v-row>
                    <br/>
                    <v-row>
                        <v-btn v-on:click="saveCurrent()" color="primary">Save current</v-btn>
                    </v-row>

                    <br/>
                    <v-row v-if="isProgressing">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                        ></v-progress-circular>
                    </v-row>

                </v-container>
            </v-main>
        </v-app>
    </div>
</body>
<script>
    var app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        mounted: function () {
            this.loadKeys();
        },
        data: {
            isProgressing : false,
            webhookKeys : [

            ],
            newPublicKey : '',
            newPrivateKey: '',
        },
        methods: {
            loadKeys: function(){
                var vue = this;
                vue.isProgressing = true;

                $.ajax({
                    type: "GET",
                    url: "/test/webhook-cache.php",
                    contentType:"application/json; charset=utf-8",
                    success : function(data){
                        vue.webhookKeys = data;
                        vue.isProgressing = false;
                    },
                    error: function(e){
                        alert('Error to get webhookKeys from cache.');
                        vue.webhookKeys = [];
                        vue.isProgressing = false;
                    }
                });
            },
            addNewKey: function(){
                var vue = this;
                if( ! vue.newPublicKey ) return;
                if( ! vue.newPrivateKey ) return;
                
                var newSha1 = sha1(vue.newPublicKey);
                for( var i=0; i< vue.webhookKeys.length; ++i ){
                    if( vue.webhookKeys[i].sha1 == newSha1 ){
                        return;
                    }
                }

                vue.webhookKeys.push({
                    'sha1' : newSha1,
                    'publicKey' : vue.newPublicKey,
                    'privateKey' : vue.newPrivateKey,
                    'created' : (new Date().toISOString()), 
                });
            },
            deleteKey: function(key){
                var vue = this;
                for( var i=0; i< vue.webhookKeys.length; ++i ){
                    if( vue.webhookKeys[i].sha1 == key.sha1 ){
                        vue.webhookKeys.splice(i, 1);
                        return;
                    }
                }
            },
            saveCurrent: function(){
                var vue = this;
                vue.isProgressing = true;
                $.ajax({
                    type: "POST",
                    url: "/test/webhook-cache.php",
                    contentType:"application/json; charset=utf-8",
                    data: JSON.stringify(vue.webhookKeys),
                    success : function(){
                        vue.isProgressing = false;
                        vue.loadKeys();
                    },
                    error: function(e){
                        alert('Failed to save webhookKeys into cache.');
                        vue.isProgressing = false;
                    }
                });
            }
        }
    });
</script>

</html>