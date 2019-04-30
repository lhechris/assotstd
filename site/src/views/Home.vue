<template>
  <!--<v-container>
    <v-layout
      text-xs-center
      wrap
    >
      <div class="actu_up" ></div>
      <div class="actu">
      <p><iframe src="http://www.youtube.com/embed/Jieco-43WNI" frameborder="0" width="531" height="298"></iframe></p>
      <p></p>
      </div>
      <div class="actu_down"></div>
    </v-layout>
  </v-container>-->
  <div>
    <div class="actu_up" ></div>
    <div class="actu">
    <p><iframe src="http://www.youtube.com/embed/Jieco-43WNI" frameborder="0" width="531" height="298"></iframe></p>
    <p></p>
    </div>
    <div class="actu_down"></div>
  </div>
</template>

<script>
// @ is an alias to /src
//import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'home',
}
</script>

<style>
.actu_up{
  width: 720px;
	height:216px;
	padding-left:80px;
	background-image:url(../assets/actu_up.png);
  background-repeat:no-repeat;
}
.actu{
  width: 660px;
	margin-top:-15px;
	padding-left:115px;
	background-image:url(../assets/actu.png);
  background-repeat:repeat;
}
.actu_down{
    width: 720px;
	height:216px;
	margin-top:-15px;
	padding-left:80px;
	background-image:url(../assets/actu_down.png);
    background-repeat:no-repeat;
}

</style>
