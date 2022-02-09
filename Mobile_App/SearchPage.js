import { StyleSheet, Text, View} from 'react-native';
import {useState} from "react";
import axios from 'axios';
import {ListItem, TextInput} from "@react-native-material/core";

export const SearchPage = ({ navigation }) => {
    const [text, onChangeText] = useState("query");
    const [results, onChangeResults] = useState([]);

    return (
       <View style={styles.container}>
           <TextInput placeholder="search" variant="standard" onChangeText={(new_text) => {

               if(new_text.length === 0) {
                   onChangeResults([]);
               } else {
                   axios.get("http://www.uniquechange.com/fwApp/api/search.php?l=5&q=" + new_text, { withCredentials: true })
                       .then(response => {
                           onChangeResults(response.data);
                       })
                       .catch(error => {
                           console.log(error);
                       });
               }

               onChangeText(new_text);
           }} value={text}/>

           <>
            { results.map((v) => <ListItem title={v.ID + " | " + v.title} key={v.ID} onPress={() => {
                navigation.navigate("questions", {questionId: v.ID});
            }}/>)}
           </>
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
