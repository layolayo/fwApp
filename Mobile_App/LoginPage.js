import { StyleSheet, View} from 'react-native';
import {useState} from "react";
import axios from 'axios';
import {Button, ListItem, TextInput} from "@react-native-material/core";
import {instance} from "./Networking";

export const LoginPage = ({ navigation }) => {
    const [email, onChangeEmail] = useState("");
    const [password, onChangePassword] = useState("");
    const [passwordInput, onChangePasswordInput] = useState();

    return (
       <View style={styles.container}>
           <View style={styles.innerContainer}>
               <TextInput autoCapitalize="none" autoCorrect={false} onSubmitEditing={() => passwordInput.focus()} keyboardType='email-address' returnKeyType="next" placeholder="Email" variant="standard" onChangeText={onChangeEmail} value={email}/>
               <TextInput autoCapitalize="none" autoCorrect={false} ref={(input)=> onChangePasswordInput(input) } returnKeyType="go" placeholder="password" variant="standard" onChangeText={onChangePassword} value={password} secureTextEntry/>
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

                   await instance.post('http://www.uniquechange.com/fwApp/api/mobile_auth.php', formBody, config)
                       .then(response => {
                           console.log("Status: ", response.data);
                           if(response.data.status === "ok") {
                               navigation.replace("search", {token: response.data.token});
                           }
                       })
                       .catch(error => {
                           console.log(error);
                       });
               }}/>
           </View>
       </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
        justifyContent: 'flex-start',
    },
    innerContainer: {
        flex: 1,
        backgroundColor: '#fff',
        marginLeft: 16,
        marginRight: 16,
        marginTop: 48,
        justifyContent: 'flex-start',
    },
});
