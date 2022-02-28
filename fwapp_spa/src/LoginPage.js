import {useState} from "react";
import axios from "axios";
import logo from "./logo.svg";
import { useParams, useNavigate } from "react-router-dom";

export const LoginPage = () => {
    const [email, onChangeEmail] = useState("");
    const [password, onChangePassword] = useState("");
    const [passwordInput, onChangePasswordInput] = useState();

    let navigate = useNavigate();

    return (
        <div>
            <input autoCapitalize="none" autoCorrect={false} onSubmit={() => passwordInput.focus()} placeholder="Email" onChange={(v) => onChangeEmail(v.target.value)} value={email}/>
            <input autoCapitalize="none" autoCorrect={false} ref={(input)=> onChangePasswordInput(input) } placeholder="password" onChange={(v) => onChangePassword(v.target.value)} value={password} secureTextEntry/>
            <button onClick={async () => {
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
                            // navigation.replace("search", {token: response.data.token});
                            navigate("/phase");
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }}>Log In</button>
        </div>
    );
};
