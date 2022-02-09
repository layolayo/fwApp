import { StyleSheet, View} from 'react-native';
import {useState} from "react";
import axios from 'axios';
import {Button, ListItem, TextInput} from "@react-native-material/core";

export const LoginPage = ({ navigation }) => {
    const [email, onChangeEmail] = useState("");
    const [password, onChangePassword] = useState("");

    return (
       <View style={styles.container}>
           <TextInput placeholder="you@email.com" variant="standard" onChangeText={onChangeEmail} value={email}/>
           <TextInput placeholder="password" variant="standard" onChangeText={onChangePassword} value={password}/>
           <Button title={"Log In"} onPress={async () => {
               console.log("Logging in");

               const config = {
                   headers: {
                       'Content-Type': 'application/x-www-form-urlencoded'
                   }
               }

               const details = {
                   email: email,
                   password: password,
               };

               const formBody = Object.keys(details).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(details[key])).join('&');

               await axios.post('http://www.uniquechange.com/fwApp/api/mobile_auth.php', formBody, config)
                   .then(response => {
                       console.log("Status: ", response.data.status);
                       if(response.data.status === "ok") {
                           navigation.navigate("search", {token: response.data.token});
                       }
                   })
                   .catch(error => {
                       console.log(error);
                   });
           }}/>
       </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
        marginLeft: 16,
        marginRight: 16,
        marginTop: 48,
        justifyContent: 'flex-start',
    },
});
