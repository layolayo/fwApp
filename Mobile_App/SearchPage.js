import { StyleSheet, Text, View} from 'react-native';
import {useState} from "react";
import axios from 'axios';
import {ListItem, TextInput} from "@react-native-material/core";
import {instance} from "./Networking";

export const SearchPage = ({ route, navigation }) => {
    const { token } = route.params;
    const [text, onChangeText] = useState("");
    const [results, onChangeResults] = useState([]);

    return (
       <View style={styles.container}>
           <View style={styles.innerContainer}>
               <TextInput placeholder="search" variant="standard" onChangeText={(new_text) => {

                   if(new_text.length === 0) {
                       onChangeResults([]);
                   } else {
                       instance.get("https://facilitatedwriting.com/fwApp/api/search.php?l=5&q=" + new_text, { headers: {"X-Auth-Token": token} })
                           .then(response => {
                               if(response.data.error != null) {
                                   console.log("Network error: ", response.data);
                               } else {
                                   onChangeResults(response.data);
                               }
                           })
                           .catch(error => {
                               console.log(error);
                           });
                   }

                   onChangeText(new_text);
               }} value={text}/>

               <>
                { results.map((v) => <ListItem title={v.ID + " | " + v.title} key={v.ID} onPress={() => {
                    navigation.navigate("questions", {token: token, questionSetId: v.ID});
                }}/>)}
               </>
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
