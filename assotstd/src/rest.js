import axios from 'axios';


export class restapi {
    baseurl= '/api';
    //baseurl='http://localhost/api';
    token = "";    

    getPage(name) {
      
     return axios.get(this.baseurl+'/page/num/'+name).then(response =>{        
        return response.data;      
     })
    }
    
    getListPages() {
      
        return axios.get(this.baseurl+'/page/list').then(response =>{        
           return response.data;      
        })
       }
       
    postPage(data) {
        return axios.post(this.baseurl+'/page',data).then(response =>{        
            return response.data;      
         })
     }
     isRegister() {
        return axios.get(this.baseurl+'/register/is',{withCredentials: true}).then(response =>{        
            return response.data;      
         })
     }

     postLogin(login) {
        return axios.post(this.baseurl+'/register',login,{withCredentials: true}).then(response =>{        
            return response.data;      
         })
     }
     postLogout() {
        return axios.post(this.baseurl+'/unregister',).then(response =>{        
            return response.data;      
         })
     }     
}

  