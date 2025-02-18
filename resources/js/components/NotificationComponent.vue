<template>

  <!-- <li class="dropdown">
      <a id="navbarDropdown" class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span id="notify_num" v-show="unreadnotifications.length > 0">{{unreadnotifications.length}}</span>
      </a>
  
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" v-show="unreadnotifications.length > 0" @click="markAsRead">Mark all as read!</a>
          <a class="dropdown-item" v-for="(unread, index) in unreadnotifications" :key="index" @click="showPost(index)">
              <h5>{{unread.data.comment_status}}</h5>
              <p>{{unread.data.post_title}}</p>
              <p>{{(unread.created_at)}}</p>
          </a>
          <a class="dropdown-item" v-show="unreadnotifications.length == 0">No new notifications</a>
      </div>
  </li> -->
  
  
  


  <div class="dropdown">
    <button class="btn dropdown-toggl" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      <IconNotification/>
      <span id="notify_num" v-show="unreadnotifications.length > 0">{{unreadnotifications.length}}</span>
    </button>
    <ul class="dropdown-menu">
      <a class="dropdown-item" v-show="unreadnotifications.length > 0" @click="markAsRead">Marquer tout comme lu</a>
      <a class="dropdown-item" v-for="(unread, index) in unreadnotifications" :key="index" @click="showPost(index)">
        <p><strong>{{unread.data.user_name}}</strong> a commenté <br> votre annonce</p>  
        <p id="moment">{{moment(unread.created_at).fromNow()}}</p>
      </a>
      <a class="dropdown-item" v-show="unreadnotifications.length == 0">Pas de nouvelles notifications</a>
    </ul>
  </div>
</template>

<script setup lang="ts">
import IconNotification from './icons/IconNotification.vue'
import { ref, onMounted } from 'vue';
import axios from 'axios';
import moment from 'moment';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

const token = localStorage.getItem('token');

const clientHttp = axios.create({
  baseURL: `${backendUrl}/api/`,
  headers: {
    Accept: "application/json",
    Authorization: `Bearer ${token}`,
  }
});

const unreadnotifications = ref([]);

const getNotifications = async () => {
  try {
    const response = await clientHttp.get('unreadNotifications')
    unreadnotifications.value = response.data;
    // console.log(unreadnotifications);
  } catch (error) {
    // console.log(error);
  }
};

const markAsRead = () => {
  clientHttp.get('markAsRead')
    .then((response) => {
      location.reload();
    })
    .catch((errors) => {
      // console.log(errors);
    });
};

// console.log(unreadnotifications)

const showPost = (index: number) => {
  const ad_id = unreadnotifications.value[index]?.data?.ad_id;
  const url = 'ad/' + ad_id;
  // console.log("Redirecting to:", url);
  location.href = url;
};

onMounted(() => {
  getNotifications();
  const interval = setInterval(getNotifications, 500);
  return () => clearInterval(interval); // Cleaning up the interval
});
</script>


<style scoped>
  .dropdown-item p{
  cursor: pointer;
  }

  .dropdown-toggl{
  box-shadow: none;
  }

  .dropdown-toggl:focus{
  border: none;
  }

  .dropdown-toggl:hover{
  box-shadow: none;
  }

  .dropdown-item{
  cursor: pointer;
  }

  #notify_num{
  font-size: 10px;
  padding: 2px 6px;
  top: -7px;
  background: #ff5722;
  border-radius: 100%;
  color: #fff;
  font-weight: 600;
  width: 19px;
  height: 19px;
  }

  #moment{
  font-size: 10px;
  color: rgb(134, 133, 133);
  }
</style>