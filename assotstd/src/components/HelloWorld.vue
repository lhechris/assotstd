<template>
  <div class="hello">
    <h1>Administration des pages</h1>
    <div v-if="isregister==true">
      <button type="button" class="btn btn-primary" v-on:click="logout()">Se déconnecter</button>
      <page></page>
    </div>
    <div v-else>
    <div class="loginmodal-container">
      <h1>Login to Your Account</h1><br>
      <h2 v-if="badlogin!=''" class="alert alert-danger">{{badlogin}}</h2><br>
      <div class="form-group">
        <input type="text"     class="form-control" v-model="user" placeholder="Username">
        <input type="password" class="form-control" v-model="pass" placeholder="Password">
        <input type="submit"   name="login" class="form-control login loginmodal-submit" value="Login" v-on:click="login()">
      </div>
      <div class="login-help">
        <a href="#">Register</a> - <a href="#">Forgot Password</a>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import Page from './Page'
import {restapi} from '../rest' 

export default {
  name: 'HelloWorld',
  components:{Page},
  
  data()  {
    return {
      user :'',
      pass: '',
      isregister:false,
      badlogin:''
    }
  },

  created : function() {
    this.get();
  },

  methods : {
      get: function() {
        var api = new restapi();
        var self = this;
        this.isregister=false;
        api.isRegister().then(response=> {
          if (response=="oui") {
            self.isregister=true;
          }
        })

      },

      login: function (){
        var api = new restapi();
        var self = this;

        var data = new FormData();
        data.append("login",self.user);
        data.append("pass",self.pass);
        api.postLogin(data).then(response=>{
          if (self.response=="oui") {
            self.isregister=true;
            self.badlogin="";
          } else {
            self.badlogin=response;
          }
          self.get();
          
        });
      },
      logout: function() {
        var api = new restapi();
        var self = this;
        api.postLogout().then(()=>{
          self.isregister=false;
          self.get();
        });
      } 
  }
  
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}

.avertissement {
  color: #c01e1e
}

.container{
  text-align:left;
}

.loginmodal-container {
  padding: 30px;
  max-width: 350px;
  width: 100% !important;
  background-color: #F7F7F7;
  margin: 0 auto;
  border-radius: 2px;
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
  overflow: hidden;
  font-family: roboto;
}

.loginmodal-container h1 {
  text-align: center;
  font-size: 1.8em;
  font-family: roboto;
}

.loginmodal-container input[type=submit] {
  width: 100%;
  display: block;
  margin-bottom: 10px;
  position: relative;
}

.loginmodal-container input[type=text], input[type=password] {
  height: 44px;
  font-size: 16px;
  width: 100%;
  margin-bottom: 10px;
  -webkit-appearance: none;
  background: #fff;
  border: 1px solid #d9d9d9;
  border-top: 1px solid #c0c0c0;
  /* border-radius: 2px; */
  padding: 0 8px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}

.loginmodal-container input[type=text]:hover, input[type=password]:hover {
  border: 1px solid #b9b9b9;
  border-top: 1px solid #a0a0a0;
  -moz-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
}

.loginmodal {
  text-align: center;
  font-size: 14px;
  font-family: 'Arial', sans-serif;
  font-weight: 700;
  height: 36px;
  padding: 10px 8px;
/* border-radius: 3px; */
/* -webkit-user-select: none;
  user-select: none; */
}

.loginmodal-submit {
  /* border: 1px solid #3079ed; */
  border: 0px;
  color: #fff;
  text-shadow: 0 1px rgba(0,0,0,0.1); 
  background-color: #4d90fe;
  padding: 17px 0px;
  font-family: roboto;
  font-size: 14px;
  /* background-image: -webkit-gradient(linear, 0 0, 0 100%,   from(#4d90fe), to(#4787ed)); */
}

.loginmodal-submit:hover {
  /* border: 1px solid #2f5bb7; */
  border: 0px;
  text-shadow: 0 1px rgba(0,0,0,0.3);
  background-color: #357ae8;
  /* background-image: -webkit-gradient(linear, 0 0, 0 100%,   from(#4d90fe), to(#357ae8)); */
}

.loginmodal-container a {
  text-decoration: none;
  color: #666;
  font-weight: 400;
  text-align: center;
  display: inline-block;
  opacity: 0.6;
  transition: opacity ease 0.5s;
} 

.login-help{
  font-size: 12px;
}




</style>
