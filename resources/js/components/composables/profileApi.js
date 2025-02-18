import {ref} from 'vue';
import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import router from '@/router';
import { useRoute } from 'vue-router';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

export default function useProfile(){
    const token = localStorage.getItem('token');
    // const user = localStorage.getItem('userId')

    const clientHttp = axios.create(
        {
            baseURL: `${backendUrl}/api/`,
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            }
        }
    )

    const statusData = ref({});

    const getUser = async ()=>{
        if (token){
            try{
                // const email = router.currentRoute.params.email;
                const statusResponse = await clientHttp.get(`profile`)
                console.log(statusResponse.data);
        
                if(statusResponse.status === 200){
                    statusData.value = statusResponse.data
                }
            } catch(error){
                console.log(error);
                
            }
        }
    }


    const updateProfile = async () => {
        try {
          // Envoyer les données de l'étape trois une fois soumises
          // Vous pouvez également ajouter des validations ici
          // et déclencher l'événement resetForm une fois le formulaire soumis avec succès
          const response = await clientHttp.put('profile', statusData.value);
          toast.success(response.data.success)
          console.log(response.data);
          // router.replace('/adsList')
        } catch (error) {
        //   toast.error(error.response.data.message, {
        //       autoClose: 1000,
        //       });
          console.error('Error submitting', error);
        }
      };

      const passwordData = ref({
        current_password: '',
        new_password: '',
        confirm_password: ''
      });


    const updatePassword = async () => {
        try {
          // Envoyer les données de l'étape trois une fois soumises
          // Vous pouvez également ajouter des validations ici
          // et déclencher l'événement resetForm une fois le formulaire soumis avec succès
          const response = await clientHttp.put('password', passwordData.value);
          toast.success(response.data.message)
          console.log(response.data);
          passwordData.value.current_password = '';
          passwordData.value.new_password = '';
          passwordData.value.confirm_password = '';
          // router.replace('/adsList')
        } catch (error) {
        //   toast.error(error.response.data.message, {
        //       autoClose: 1000,
        //       });
          console.error('Error submitting', error);
        }
    };

    
    return{
        getUser,
        updateProfile,
        statusData,
        updatePassword,
        passwordData
    }
}