import {useState} from "react";
import axios from "axios";
import {useDispatch} from "react-redux";
import {login} from "./userDetailsSlice";


export const LoginPage = () => {
    const [email, onChangeEmail] = useState("");
    const [password, onChangePassword] = useState("");

    const dispatch = useDispatch()

    return (
        <div className={"text-center"}>
            <style>
                {"html,\n" +
                    "body {\n" +
                    "    height: 100%;\n" +
                    "}\n" +
                    "\n" +
                    "body {\n" +
                    "    display: -ms-flexbox;\n" +
                    "    display: -webkit-box;\n" +
                    "    display: flex;\n" +
                    "    -ms-flex-align: center;\n" +
                    "    -ms-flex-pack: center;\n" +
                    "    -webkit-box-align: center;\n" +
                    "    align-items: center;\n" +
                    "    -webkit-box-pack: center;\n" +
                    "    justify-content: center;\n" +
                    "    padding-top: 40px;\n" +
                    "    padding-bottom: 40px;\n" +
                    "    background-color: #f5f5f5;\n" +
                    "}\n" +
                    "\n" +
                    ".form-signin {\n" +
                    "    width: 100%;\n" +
                    "    max-width: 330px;\n" +
                    "    padding: 15px;\n" +
                    "    margin: 0 auto;\n" +
                    "}\n" +
                    "\n" +
                    ".form-signin .checkbox {\n" +
                    "    font-weight: 400;\n" +
                    "}\n" +
                    "\n" +
                    ".form-signin .form-control {\n" +
                    "    position: relative;\n" +
                    "    box-sizing: border-box;\n" +
                    "    height: auto;\n" +
                    "    padding: 10px;\n" +
                    "    font-size: 16px;\n" +
                    "}\n" +
                    "\n" +
                    ".form-signin .form-control:focus {\n" +
                    "    z-index: 2;\n" +
                    "}\n" +
                    "\n" +
                    ".form-signin input[type=\"email\"] {\n" +
                    "    margin-bottom: -1px;\n" +
                    "    border-bottom-right-radius: 0;\n" +
                    "    border-bottom-left-radius: 0;\n" +
                    "}\n" +
                    "\n" +
                    ".form-signin input[type=\"password\"] {\n" +
                    "    margin-bottom: 10px;\n" +
                    "    border-top-left-radius: 0;\n" +
                    "    border-top-right-radius: 0;\n" +
                    "}"}
            </style>
        <div className="form-signin">
            <img className="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt=""
                 width="72" height="72"/>
                <h1 className="h3 mb-3 font-weight-normal">Please sign in</h1>
                <label htmlFor="inputEmail" className="sr-only">Username</label>
                <input type="email" id="inputEmail" name="inputEmail" className="form-control"
                       placeholder="Email address" required autoFocus onChange={(v) => onChangeEmail(v.target.value)}/>
                    <label htmlFor="inputPassword" className="sr-only">Password</label>
                    <input type="password" name="inputPassword" id="inputPassword" className="form-control"
    placeholder="Password" required onChange={(v) => onChangePassword(v.target.value)}/>
                        <button className="btn btn-lg btn-primary btn-block" onClick={async () => {
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
                                        // navigate("/phase");
                                        dispatch(login(response.data.token));
                                    }
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                        }>Sign in</button>
                        <p className="mt-5 mb-3 text-muted">&copy; 2017-2022</p>
        </div>
        </div>
    );
};

//TODO: make this look the same as the old one
