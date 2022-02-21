import {useState} from "react";
import axios from "axios";
import logo from "./logo.svg";

export const PhasePage = () => {
    let [phases, setPhases] = useState([]);

    const config = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    }

    const details = {
        email: "callumthom11@gmail.com",
        password: "DYadx3igBs742xG",
    };

    const formBody = Object.keys(details).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(details[key])).join('&');

    axios.post('http://www.uniquechange.com/fwApp/api/mobile_auth.php', formBody, config)
        .then(response => {
            console.log("Status: ", response.data.status);
            if(response.data.status === "ok") {
                axios.get("http://www.uniquechange.com/fwApp/api/phase.php", { withCredentials: true })
                    .then(response => {
                        if(phases.length === 0) {
                            let data = response.data;
                            setPhases(data);
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        })
        .catch(error => {
            console.log(error);
        });


    return (
        <div>
            <div>
                <div className="navbar mynav navbar-expand-lg navbar-light bg-light">
                    <ul className="navbar-nav" id="my-nav">
                        { phases.map((phase) => {
                            return (
                                <li className='phase-links' id={phase.title} onClick='qs(phase.title)'>
                                    <a className='nav-link' href={'#phase=' + phase.title}>{phase.title}
                                    </a>
                                </li>);
                        })}
                    </ul>
                </div>

                <div className="jumbotron" style={{margin: 0}}>
                    <div className="col container">

                        <h1 className="display-3" id="question-sets-for">Phases</h1>
                        <p className='lead'>1. Choose from any Phase Above</p>
                        <p className='lead'>2. Apply a filter (Optional)</p>
                    </div>
                </div>
            </div>
        </div>
    );
};
