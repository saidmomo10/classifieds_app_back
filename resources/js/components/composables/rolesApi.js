import {ref} from 'vue';
import axios from 'axios';
import router from '@/router';

const backendUrl = import.meta.env.VITE_BACKEND_URL;

export default function useRoles(){
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

    const getPermissions = async ()=>{
        if (token){
            try{
                // const email = router.currentRoute.params.email;
                const statusResponse = await clientHttp.get('permissions')
        console.log(statusResponse);
        
                if(statusResponse.status === 200){
                    statusData.value = statusResponse.data
                }
            } catch(error){
                console.log(error);
                
            }
        }
    }

    
    //Post
    const formData = ref({
        name: '',
        permission:[''],
    })

    async function addRole(){
        try {
            const roles = await clientHttp.post('roles', formData.value,{
        headers: {
            Authorization: `Bearer ${token}`,
        }
        });
            if(roles.status === 200){
                statusData.value = roles.data
            }
            console.log(roles);
            router.replace('/roles');
        }catch(error){
                // emailError.value = 'L\'adresse email doit contenir le symbole "@".';
                // passwordError.value = 'Le mot de passe doit avoir au moins 8 caractères.';
                console.log(error);
        }
    }

    const getRoleData = ref([])

    const getRole = async ()=>{
        if (token){
            try{
                // const email = router.currentRoute.params.email;
                const getRoleResponse = await clientHttp.get('roles')
        console.log(getRoleResponse);
        
                if(getRoleResponse.status === 200){
                    getRoleData.value = getRoleResponse.data
                }
            } catch(error){
                console.log(error);
                
            }
        }
    }

    const deleteRole = async (id) =>{
        if(confirm("sure ???")){
            const deleteResponse = await clientHttp.delete(`roles/${id}`)
            console.log(deleteResponse);
            
        }
        router.replace('/roles')
    }

    return{
        statusData,
        getRoleData,
        formData,
        getPermissions,
        getRole,
        addRole,
        deleteRole
    }
}