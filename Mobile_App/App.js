import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View } from 'react-native';
import { SearchPage } from "./SearchPage";
import {createNativeStackNavigator} from "@react-navigation/native-stack";
import {NavigationContainer} from "@react-navigation/native";
import {QuestionsPage} from "./QuestionsPage";

const NavigationStack = createNativeStackNavigator();

export default function App() {
  return (
      <NavigationContainer>
        <NavigationStack.Navigator>
          <NavigationStack.Screen name="search" component={SearchPage}/>
          <NavigationStack.Screen name="questions" component={QuestionsPage}/>
        </NavigationStack.Navigator>
      </NavigationContainer>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});


//TODO:
// login flow
// re-enable auth on web api
// id in search results
// frequency logs
