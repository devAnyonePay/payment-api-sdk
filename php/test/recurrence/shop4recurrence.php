<?php
    include(dirname(__FILE__) . '/../basic_auth.php');

    $testshop_clientId=$_SERVER['anyonepay_testshop_stage_clientId'];
    $testshop_clientPw=$_SERVER['anyonepay_testshop_stage_clientPw'];
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
</head>

<body>
    <div id="app">
        <v-app>
            <v-main>
                <v-container>

                    <h1>Fake Merchant Shopping mall</h1>
                    <h3>Test making new subscription request</h3>
                    <h4>2021.02.03 updated</h4>
                    <br/>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-select
                                :items="profiles"
                                filled
                                label="Profile"
                                v-model="profile"
                                @change="changeProfile"
                            ></v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-expansion-panels>
                            <v-expansion-panel>
                                <v-expansion-panel-header>
                                    Beta User mode
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <v-textarea
                                    name="input-7-1"
                                    label="When you use webhook to test for."
                                    value="Beta user can below things.
1. B can see the PgChannels in BetaMode
<notice>
2020-12-09 this function is not working well (Set-Cookie has no response on Stage/Production, but localhost is working)
so If you want to be BetaUser just make the cookie (ANYONEPAY_BETA_USER=[name]) for stg.anyonepay.ph/ or www.anyonepay.ph/
"
                                    ></v-textarea>
                                    <v-row>
                                        <v-col cols="12" sm="12" md="12">
                                            <v-text-field label="Beta User Name" v-model="betaUserName"></v-text-field>
                                            <v-btn v-on:click="makeBetaUser" color="primary">I wanna beta user.</v-btn>
                                            <v-btn v-on:click="removeBetaUser" color="primary">I don't wanna beta user.</v-btn>
                                        </v-col>
                                    </v-row>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="ClientId" v-model="clientId"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="ClientSecret" v-model="clientSecret"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="StoreId" v-model="storeId"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-select
                                :items="billingMethods"
                                filled
                                label="Billing Method"
                                v-model="billingMethod"
                            ></v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-select
                                :items="intervals"
                                filled
                                label="Billing Interval(Cycle)"
                                v-model="interval"
                            ></v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Interval Count (It should higher than 0)" v-model="intervalCount"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Start Billing Date (It should be future, Not Today If PGC09)" v-model="startDate"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Amount (It should higher than Fee)" v-model="amount"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-expansion-panels>
                        <v-expansion-panel>
                            <v-expansion-panel-header>
                                Basic Info  (Optional)
                            </v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-row>
                                    <v-col cols="12" sm="12" md="12">
                                        <v-text-field label="FirstName" v-model="firstName"></v-text-field>
                                        <v-text-field label="MiddleName" v-model="middleName"></v-text-field>
                                        <v-text-field label="lastName" v-model="lastName"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" sm="12" md="12">
                                        <v-text-field label="Customer email" v-model="email"></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12" sm="12" md="12">
                                        <v-text-field label="Phone" v-model="phone"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>
                                Billing Address (Optional)
                            </v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-row>
                                    <v-col cols="12" sm="12" md="12">
                                        <v-text-field label="province" v-model="province"></v-text-field>
                                        <v-text-field label="city" v-model="city"></v-text-field>
                                        <v-text-field label="street" v-model="street"></v-text-field>
                                        <v-text-field label="addr1" v-model="addr1"></v-text-field>
                                        <v-text-field label="postCode" v-model="postCode"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                            <v-expansion-panel-header>
                                Checkout Options (DirectPay)
                            </v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <v-row>
                                    <v-col cols="12" sm="12" md="12">
                                        <v-select
                                                :items="pgChannels"
                                                item-text="channel"
                                                item-value="value"
                                                filled
                                                label="Payment Gateway Channel"
                                                v-model="pgChannel"
                                        >
                                            <template v-slot:append-outer>
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-icon v-on="on">mdi-help-circle-outline</v-icon>
                                                    </template>
                                                    The pgchannel to use for payment.
                                                </v-tooltip>
                                            </template>
                                        </v-select>
                                    </v-col>
                                </v-row>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-expansion-panels>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Product" v-model="product"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Product Description" v-model="productDescription"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Reference No" v-model="refNo"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Redirect-Uri(Return when finished to pay)" v-model="redirectUri">
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Webhook-Uri" v-model="webhookUri"></v-text-field>
                            <v-textarea
                                name="input-7-1"
                                label="When you use webhook to test for."
                                value="
                                That situation is like in below.
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
                            
                            <v-card>
                                <v-card-title primary-title>
                                <div>
                                    <div class="caption mb-0">WebHook will works with below merchant public/private key pairs in memory cache.</div>
                                    <div id="example-1" style="background-color: #DDD;padding:10px; border:1px solid #aaa;">
                                        <div v-for="key in webhookKeys" :key="key.publicKey" style="border:1px solid #111;padding:10px;">
                                            <div class="caption">
                                                <span>Created : </span><span>{{ key.created }}</span>
                                            </div>
                                            <div class="caption"><span>Public SHA1 : </span><span>{{ key.sha1 }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                </v-card-title>
                                <v-card-actions>
                                    <a href="/test/webhook-config.php">Configure of webhook's merchant public, privatekey for testing</a>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Cancel-Uri" v-model="cancelUri"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-btn v-on:click="startpay()" color="primary">Use above info to pay</v-btn>
                    </v-row>

                    <br/>
                    <v-row v-if="isProgressing">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                        ></v-progress-circular>
                    </v-row>
                    <br/>

                    <v-row>
                        <v-textarea
                            name="input-7-1"
                            label="Result of Request"
                            v-model="resultOfRequest"
                            hint="Hint text"
                        ></v-textarea>
                    </v-row>

                    <v-row v-if="moveToCheckoutPage">
                        <v-btn v-on:click="goCheckoutNow()" color="primary">Go Checkout Now</v-btn>
                    </v-row>

                </v-container>
            </v-main>
        </v-app>
    </div>
</body>
<script>
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function setCookie(cname, cvalue, exdays) {
        var pathname = window.location.pathname;

        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path="+pathname;
    }

    function saveCookie(prof, s) {
        setCookie("ANYONE_PROFILE_"+prof, JSON.stringify(s));
    }

    function loadCookie(prof) {
        var x = getCookie("ANYONE_PROFILE_"+prof);
        if (!x) return;
        return JSON.parse(x);
    }

    var app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        mounted: function () {
            this.loadCache();
            this.loadWebhookKeys();
        },
        data: {
            betaUserName : '',

            isProgressing : false,
            profiles : [
                'STAGE', 'SANDBOX', 'PRODUCTION_INTERNAL'
            ],
            profile : 'STAGE',

            billingMethods : [
                'RECURRENCE'
            ],
            billingMethod : 'RECURRENCE',

            pgChannels : [
              { channel: 'None', value: '' },
              { channel: 'Paymaya Vault Subscription(PGC09)', value: 'PGC09' },
            ],
            pgChannel : 'PGC09',

            intervals : [
                'DAY',
                'MONTH',
                'YEAR',
            ],
            interval : 'DAY',
            intervalCount : 1,
            startDate : (()=>{
                var current = new Date(); //'Mar 11 2015' current.getTime() = 1426060964567
                var followingDay = new Date(current.getTime() + 86400000); // + 1 day in ms
                return followingDay.toISOString().split('T')[0];
            })(),

            clientId: "<?php echo $testshop_clientId?>",
            clientSecret: "<?php echo $testshop_clientPw?>",
            
            storeId: "2005271259396999814",
            amount: 20.00,

            firstName: "John",
            middleName: "F",
            lastName: "Kenedy",

            email: "test@anyonepay.ph",
            phone: "639123456789",

            province : "Metro Manila",
            city : "Makati",
            street : "Street 001",
            addr1 : "A Building",
            postCode : "123456",

            product: "test Product A",
            productDescription: "test Product A Description",
            
            refNo: String((new Date()).getTime()),
            
            redirectUri: 'http://testshop.anyonepay.ph/test/payResult.php?v=1',
            webhookUri: 'http://testshop.anyonepay.ph/test/webhook.php?v=verifyOrCompletion&call=STAGE',
            cancelUri: 'http://testshop.anyonepay.ph/test/payResult.php?v=cancel',

            resultOfRequest: '',
            moveToCheckoutPage: '',
            // ---------------------------------------------------------------------------------------------------------
            webhookKeys: [],
        },
        methods: {
            loadCache: function(){
                var ck = loadCookie(this.profile);
                if (!ck) return;
                for (var k in ck) {
                    if (this[k]) {
                        this[k] = ck[k];
                    }
                }
                this.refNo = String((new Date()).getTime());
            },
            changeProfile: function() {
                this.loadCache();
            },
            startpay: function () {
                var vue = this;

                var dt = {
                    profile : this.profile,

                    clientId: this.clientId,
                    clientSecret: this.clientSecret,

                    storeId: this.storeId,
                    billingMethod: this.billingMethod,

                    pgChannel : this.pgChannel,
                    interval: this.interval,
                    intervalCount: this.intervalCount,
                    startDate: this.startDate,

                    amount: this.amount,
                    
                    firstName: this.firstName,
                    middleName: this.middleName,
                    lastName: this.lastName,

                    email: this.email,
                    phone: this.phone,

                    province : this.province,
                    city : this.city,
                    street : this.street,
                    addr1 : this.addr1,
                    postCode : this.postCode,

                    product: this.product,
                    productDescription: this.productDescription,
                    
                    refNo: this.refNo,

                    redirectUri: this.redirectUri,
                    webhookUri: this.webhookUri,
                    cancelUri: this.cancelUri,
                };
                console.log('this parameter will be requested:', dt);
                saveCookie(vue.profile, dt);

                vue.resultOfRequest = "Requesting...";
                vue.isProgressing = true;


                $.ajax({
                    type: "POST",
                    url: "/test/recurrence/registerRecurrence.php",
                    contentType:"application/json; charset=utf-8",
                    data: JSON.stringify(dt),
                    success : function(data){
                        vue.resultOfRequest = JSON.stringify(data);
                        vue.moveToCheckoutPage = data.checkoutUrl;
                        vue.isProgressing = false;
                    },
                    error: function(e){
                        vue.resultOfRequest = JSON.stringify(e);
                        vue.moveToCheckoutPage = '';
                        vue.isProgressing = false;
                    }
                });
            },
            goCheckoutNow : function(){
                location.href = this.moveToCheckoutPage;
            },
            loadWebhookKeys: function(){
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
            makeBetaUser : function(){
                this.callApiBetaUser(true);
            },
            removeBetaUser : function(){
                this.callApiBetaUser(false);
            },
            callApiBetaUser: function(create){
                var vue = this;
                var curProfile = vue.profile;
                if( curProfile == 'SANDBOX' ){
                    alert('We are not support sandbox mode.');
                    return;
                }

                if( ! vue.betaUserName ){
                    alert('Please insert userName.');
                    return;
                }

                var host = 'https://stg.anyonepay.ph';
                if( curProfile == 'PRODUCTION_INTERNAL' ){
                    host = 'https://www.anyonepay.ph';
                }

                $.ajax({
                    type: create?"POST":"DELETE",
                    url: host+"/checkout/api/secure/iWannaBetaUser/"+encodeURIComponent(vue.betaUserName),
                    data: {},
                    contentType:"application/json; charset=utf-8",
                    crossDomain: true,
                    xhrFields: {
                        withCredentials: true
                    },
                    success : function(data){
                        vue.isProgressing = false;
                        alert('Success.');
                    },
                    error: function(e){
                        vue.isProgressing = false;
                        alert('Failed.');
                    }
                });
            },
        }
    });
</script>

</html>