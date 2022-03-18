import {FlatList, Image, SafeAreaView, StyleSheet, TouchableOpacity, View} from 'react-native';
import * as React from "react";
import {useState} from "react";
import axios from 'axios';
import {Button, Divider, ListItem, Text} from "@react-native-material/core";
import { Audio } from 'expo-av';
import {Icon} from "react-native-elements";
import {instance} from "./Networking";

Array.prototype.sortOn = function(key){
    this.sort(function(a, b){
        if(a[key] < b[key]){
            return -1;
        }else if(a[key] > b[key]){
            return 1;
        }
        return 0;
    });
}

export const QuestionsPage = ({ route, navigation }) => {
    const { token, questionSetId } = route.params;

    const [results, onChangeResults] = useState([]);
    const [sound, setSound] = React.useState();
    const [position, setPosition] = useState(0);
    const [questionList, setQuestionList] = useState();

    React.useEffect(() => {
        // Return the function to unsubscribe from the event so it gets removed on unmount
        return navigation.addListener('focus', () => {
            // Load the questions for this question set
            instance.get("http://www.uniquechange.com/fwApp/api/questions.php?id=" + questionSetId, { headers: {"X-Auth-Token": token}})
                .then(response => {
                    let data = response.data;
                    data.sortOn("qOrder");
                    console.log(data);

                    let repeatedData = [];
                    data.forEach((item) => {
                        const repeats = item.repeats == null ? 1 : item.repeats;
                        for(let i = 0; i < repeats; i++) {
                            repeatedData.push(item);
                        }
                    });
                    console.log(repeatedData);

                    onChangeResults(repeatedData);
                })
                .catch(error => {
                    console.log(error);
                });
        });
    }, [navigation]);

    React.useEffect(() => {
        return sound
            ? () => {
                console.log('Unloading Sound');
                sound.unloadAsync(); }
            : undefined;
    }, [sound]);

    const renderQuestion = ({ item, index }) => (
        <TouchableOpacity style={{marginLeft: 32, marginRight: 32}} onPress={() => {{
            setPosition(index);
            questionList.scrollToIndex({animated: true, index: index});
        }}} key={index}>
            <Text style={ index === position ? styles.selected : styles.unselected}>{item.question}</Text>
            {/*{ item.details != null && <Text>{item.details}</Text> }*/}
            { item.audio != null && <View style={{marginBottom: 16}}>
                <Icon type={"antdesign"} name={"sound"} onPress={async () => {
                    // Load and play sound
                    const {sound} = await Audio.Sound.createAsync(
                        {uri: "http://www.uniquechange.com/fwApp/audio-store/" + item.audio + ".mp3" }
                    );

                    setSound(sound);

                    console.log("Playing sound");
                    await sound.playAsync();

                    // Update highlight
                    setPosition(index);
                    questionList.scrollToIndex({animated: true, index: index});
                }}/>
            </View>}
            { item.audio_details != null && <View style={{marginBottom: 16}}>
                <Icon type={"antdesign"} name={"sound"} onPress={async () => {
                    // Load and play sound
                    const {sound} = await Audio.Sound.createAsync(
                        {uri: "http://www.uniquechange.com/fwApp/audio-store/" + item.audio_details + ".mp3" }
                    );

                    setSound(sound);

                    console.log("Playing details sound");
                    await sound.playAsync();

                    // Update highlight
                    setPosition(index);
                    questionList.scrollToIndex({animated: true, index: index});
                }}/>
                <Text style={{textAlign: "center", fontSize: 12}}>Explanation</Text>
            </View>}
            { (item.image != null && item.image_alttext != null) && <Image style={{marginLeft: "auto", marginRight: "auto", marginBottom: 16}} source={{uri: "http://www.uniquechange.com/fwApp/image-store/" + item.image + ".png", width: 128, height: 128}} accessibilityLabel={item.image_alttext}/>}
            <Divider/>
        </TouchableOpacity>
    );

    return (
   <SafeAreaView style={styles.container}>
       <FlatList data={results} extraData={position} keyExtractor={(item, index) => index} renderItem={renderQuestion} ref={(ref) => {setQuestionList(ref);}} />

       <View style={{flexDirection: 'row', justifyContent: "space-between", marginRight: 32, marginLeft: 32}}>
           <Icon size={48} type={"font-awesome-5"} name={"caret-square-down"} onPress={() => {
               let index = Math.min((position + 1), results.length - 1);
               setPosition(index);
               questionList.scrollToIndex({animated: true, index: index});
           }}/>

           <Button style={{marginLeft: 32, marginRight: 32, marginBottom: 8, flexGrow: 1}} title={"Done"} onPress={() => {
               axios.get("http://www.uniquechange.com/fwApp/api/frequency.php?id=" + questionSetId, { withCredentials: true })
                   .then(response => {
                       console.log("Frequency updated");
                   })
                   .catch(error => {
                       console.log(error);
                   }).finally(() => {
                   navigation.goBack();
               });
           }}/>

           <Icon size={48} type={"font-awesome-5"} name={"caret-square-up"} onPress={() => {
               let index = Math.max((position - 1), 0);
               setPosition(index);
               questionList.scrollToIndex({animated: true, index: index});
           }}/>
       </View>
   </SafeAreaView>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#fff',
        justifyContent: 'flex-start',
    },
    selected: {
        marginTop: 16,
        marginBottom: 16,
        fontSize: 18,
        backgroundColor: '#ffff00'
    },
    unselected: {
        marginTop: 16,
        marginBottom: 16,
        fontSize: 18,
        backgroundColor: '#fff'
    },
});
