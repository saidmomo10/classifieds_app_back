import { ref } from "vue";
// import {ref, onMounted} from 'vue'
import axios from 'axios';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

export default function useUserId(){

    const token = localStorage.getItem('token');

    const clientHttp = axios.create(
        {
            baseURL: `${backendUrl}/api/`,
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${token}`,
            }
        }
    )

    const user = ref([])

    const getAuth = async () => {
        if (token){
            try {
                const statusResponse = await clientHttp.get('user');
                
                // Vérifiez si la réponse est réussie et si elle contient des données
                if (statusResponse.status === 200 && statusResponse.data.id) {
                    // Retournez uniquement l'ID de l'utilisateur
                    statusResponse.data.id = user.value;
                } else {
                    // Si la réponse est invalide ou ne contient pas l'ID, retournez null ou une valeur par défaut
                    return null;
                }
            } catch(error) {
                console.log(error);
                // En cas d'erreur, retournez null ou une valeur par défaut
                return null;
            }
        } else {
            // Si le token est invalide ou non défini, retournez null ou une valeur par défaut
            return null;
        }
    }

    return{
        getAuth,
        user
    }
}