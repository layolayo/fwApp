import {StyleSheet, View} from 'react-native';
import * as React from "react";
import {useState} from "react";
import axios from 'axios';
import {Button, Divider, ListItem, Text} from "@react-native-material/core";
import { Audio } from 'expo-av';
import {Icon} from "react-native-elements";

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
    const { questionId } = route.params;

    const [results, onChangeResults] = useState([]);
    const [sound, setSound] = React.useState();
    const [position, setPosition] = useState(0);

    React.useEffect(() => {
        // Return the function to unsubscribe from the event so it gets removed on unmount
        return navigation.addListener('focus', () => {
            axios.get("http://www.uniquechange.com/fwApp/api/questions.php?id=" + questionId)
                .then(response => {
                    let data = response.data;
                    data.sortOn("qOrder");
                    console.log(data);
                    onChangeResults(data);
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

    return (
   <View style={styles.container}>
       <View style={{marginLeft: 32, marginRight: 32, marginTop: 32}}>
       <>
        { results.map((v, index) =>
            new Array(v.repeats == null ? 1 : v.repeats).fill(0).map(() =>
                <View key={v.id}>
                    <Text style={ index === position ? styles.selected : styles.unselected}>{v.question}</Text>
                    { v.details != null && <Text>{v.details}</Text> }
                    { v.audio != null && <Icon type={"antdesign"} name={"sound"} onPress={async () => {
                        const {sound} = await Audio.Sound.createAsync(
                            {uri: "http://www.uniquechange.com/fwApp/audio-store/" + v.audio + ".mp3" }
                        );

                        setSound(sound);

                        console.log("Playing sound");
                        await sound.playAsync();
                    }}/>}
                    <Divider/>
                </View>
            )
        )}
       </>
           <Button title={"Done"} onPress={() => navigation.goBack()}/>

       <View style={{flexDirection: 'row'}}>
           <Icon size={48} type={"font-awesome-5"} name={"caret-square-down"} onPress={() => setPosition(Math.min((position + 1), results.length - 1))}/>
           <Icon size={48} type={"font-awesome-5"} name={"caret-square-up"} onPress={() => setPosition(Math.max((position - 1), 0))}/>
       </View>
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
    selected: {
        backgroundColor: '#ffff00'
    },
    unselected: {
        backgroundColor: '#fff'
    },
});
