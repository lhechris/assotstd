<template>
<div class="container">
<div>Modification des pages</div>
    <div v-if="waiting==true"> Chargement....</div>
    <div v-else>
        <div class="row">
            <span class="col-md-5"></span>
            <select v-model="pagenum" v-on:change="refresh()" class="col-md-2" >
                <option v-for="p in pages" v-bind:key="'page'+p.id" v-bind:value="p.id">{{p.name}}</option>
            </select>
            <span class="col-md-5"></span>
        </div>
        <div class="row">
            <div class="col-md-12">
                <editor rows="50" class="form-control" v-model="texte" api-key="n9zk1fj9jf7aqke6igai1s5fpvfvvhwwbdzgedph4wwcbrl7" 
                    :init="{toolbar: 'undo redo | formatselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help'}"></editor>
                <!--<textarea id="basic-example" class="form-control col-md-10" rows="20"  v-model="texte" style="font-size:14px"></textarea>-->
            </div>
        </div>
        <div class="row">
            <span class="col-md-4"></span>
            <button type="button" class="btn btn-primary col-md-2" v-on:click="sauver()">Sauver</button>
            <span class="col-md-2" />
            <button type="button" class="btn btn-primary col-md-2" v-on:click="annuler()">Annuler</button>
        </div>    
    </div>
</div>
</template>

<script>

import {restapi} from '../rest' 
import Editor from '@tinymce/tinymce-vue';

export default {
  name: 'Page',
  components :{'editor': Editor},

  data () {
    return {
      waiting : true,
      texte: "",
      origtexte:"",
      pagenum:"",
      pages:[]
    }
  },

  created: function() {
      this.get()
  },
  
   methods:{
        get: function (){
            var api = new restapi();
            var self=this;
            api.getListPages().then(response=>{
                self.pages=response;
                self.pagenum=self.pages[0]["id"];
                self.refresh();
            })
        },

        refresh: function() {
            var api = new restapi();
            var self=this;
            self.waiting=true;
            api.getPage(this.pagenum).then(response=>{
                self.texte=response;
                self.origtexte=response;
                self.waiting=false;
            })            
        },

        sauver: function() {
            var api = new restapi();
            var data = new FormData();
            data.append("id",this.pagenum);
            data.append("texte",this.texte);
            var self=this;
            api.postPage(data).then(()=>{
                self.origtexte=self.texte;
            })
        },

        annuler: function() {
            this.texte=this.origtexte;
        },

    }

}
</script>

