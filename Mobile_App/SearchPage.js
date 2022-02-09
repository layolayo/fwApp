import {SafeAreaView, TextInput} from "react-native-web";
import axios from 'axios';

const getSearchResults = (query) => {
    this.setState({
        fromFetch: false,
        loading: true,

    })
    axios.get("http://www.uniquechange.com/fwApp/api/search.php?l=5&q="+query)
        .then(response => {
            console.log('getting data from axios', response.data);
            setTimeout(() => {
                this.setState({
                    loading: false,
                    axiosData: response.data
                })
            }, 2000)
        })
        .catch(error => {
            console.log(error);
        });
}

const SearchPage = () => {
    const [text, onChangeText] = React.useState("query");

    return (
      <SafeAreaView>
          <TextInput onChangeText={(new_text) => {
              getSearchResults(new_text);
              onChangeText(new_text);
          }}
                     value={text}/>
          { this.state.axiosData.map((v) => <Text>{v}</Text>)}
      </SafeAreaView>
    );
};

export default SearchPage;
