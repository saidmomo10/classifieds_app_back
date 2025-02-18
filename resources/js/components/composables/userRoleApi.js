import {ref} from 'vue';
import axios from 'axios';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

export default function useUserRoles(){
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


    const statusData = ref([])
    const userRole = ref('')


    const getUserRole = async ()=>{
        if (token){
            try{
                // const email = router.currentRoute.params.email;
                const statusResponse = await clientHttp.get('user-role')
        console.log(statusResponse.data);
        
                if(statusResponse.status === 200){
                    statusData.value = statusResponse.data

                    const statuses = statusData.value;

                    // Vérifier si le tableau n'est pas vide
                    if (statuses.length > 0) {
                        // Accéder aux éléments du tableau
                        statuses.forEach(status => {
                            userRole.value = status.name; // Afficher chaque élément du tableau
                            console.log(userRole.value);
                            // Faites quelque chose avec chaque élément, par exemple :
                            // console.log(status.name); // Afficher le nom de chaque élément
                        });
                    } else {
                        console.log("Le tableau statusData est vide.");
                    }
                }
            } catch(error){
                // console.log(error);
                
            }
        }
    }


    return{
        getUserRole,
        userRole
    }
}