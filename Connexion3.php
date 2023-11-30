<!doctype html>
<html lang="fr">
<head>
<link type="text/css" rel="stylesheet" href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css" />
<link type="text/css" rel="stylesheet" href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.css" />
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<div id="app">
<b-container>
<input v-model="idc" placeholder="numéro de chambre"/>
<div class="container"> <div class="form-group"> <label for="exampleInputEmail1">Email address</label> <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email"> <div class="invalid-feedback"> utilisateur et/ou mot de passe invalide </div> </div> <div class="form-group"> <label for="exampleInputPassword1">Password</label> <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password"> </div> 
<b-btn variant="primary" v-on:click="ajax(idc)">valider</b-btn>
 </div>
<p>Le message est : {{ info }}</p>
</b-container>
</div>
<script>
var app = new Vue({
el: '#app',
data() {
return {
idc: 0, 
info: null,
message: ''
}
},
methods: {
ajax: function (idc, event) {
axios({
method: 'get',
url: 'http://localhost/finder/verification2.php/chambre?idc='+idc,
responseType: 'json',
})
.then(user => {
            console.log('ok');
      })
.catch(function (error) {
            console.log('error: ', error);           
     })
}
}
})</script>