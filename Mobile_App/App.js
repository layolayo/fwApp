import { StyleSheet, Text, View } from 'react-native';
import { SearchPage } from "./SearchPage";
import {createNativeStackNavigator} from "@react-navigation/native-stack";
import {NavigationContainer} from "@react-navigation/native";
import {QuestionsPage} from "./QuestionsPage";
import {LoginPage} from "./LoginPage";

const NavigationStack = createNativeStackNavigator();

import * as Sentry from 'sentry-expo';

Sentry.init({
    dsn: 'https://2d2c9f5c1dff4988b39f4b7bf11798bf@o1155143.ingest.sentry.io/6235411',
    enableInExpoDevelopment: true,
    debug: true,
    /*integrations: [
        new Sentry.ReactNativeTracing({
            // Pass instrumentation to be used as `routingInstrumentation`
            routingInstrumentation: new Sentry.ReactNativeNavigationInstrumentation(
                NavigationStack,
            )
            // ...
        }),
    ],*/
});

export default function App() {
  return (
      <NavigationContainer>
        <NavigationStack.Navigator>
          <NavigationStack.Screen name="login" component={LoginPage}/>
          <NavigationStack.Screen name="search" options={{title: 'And...?'}} component={SearchPage}/>
          <NavigationStack.Screen name="questions" options={({ route }) => ({ title: "" + route.params.questionSetId})} component={QuestionsPage}/>
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
