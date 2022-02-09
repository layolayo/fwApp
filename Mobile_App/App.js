import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View } from 'react-native';
import { SearchPage } from "./SearchPage";
import {createNativeStackNavigator} from "@react-navigation/native-stack";
import {NavigationContainer} from "@react-navigation/native";
import {QuestionsPage} from "./QuestionsPage";
import {LoginPage} from "./LoginPage";

const NavigationStack = createNativeStackNavigator();

export default function App() {
  return (
      <NavigationContainer>
        <NavigationStack.Navigator>
          <NavigationStack.Screen name="login" component={LoginPage}/>
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
