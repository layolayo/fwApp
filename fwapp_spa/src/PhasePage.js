import {useState} from "react";
import axios from "axios";
import logo from "./logo.svg";

export const PhasePage = () => {
    let [phases, setPhases] = useState([]);
    let [type, setType] = useState([]);
    let [specialism, setSpecialism] = useState([]);

    axios.get("https://facilitatedwriting.com/fwApp/api/phase.php", { withCredentials: true })
        .then(response => {
            if(phases.length === 0) {
                let data = response.data;
                setPhases(data);
            }
        })
        .catch(error => {
            console.log(error);
        });

    axios.get("https://facilitatedwriting.com/fwApp/api/type.php", { withCredentials: true })
        .then(response => {
            if(type.length === 0) {
                let data = response.data;
                setType(data);
            }
        })
        .catch(error => {
            console.log(error);
        });

    axios.get("https://facilitatedwriting.com/fwApp/api/specialism.php", { withCredentials: true })
        .then(response => {
            if(specialism.length === 0) {
                let data = response.data;
                setSpecialism(data);
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
                                <li className='phase-links' id={phase.title} onClick='qs(phase.title)' key={phase.title}>
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

                <section id="qsets" style={{/*display: 'none'*/}} className="h-100">
                    <div className="d-flex bd-highlight">
                        <div className="p-2 bd-highlight shadow-lg" style={{minWidth: '320px'}}>
                            <form className='form-check'>
                                <div>
                                    <div>
                                        <hr/>
                                        <a className='btn' data-bs-toggle='collapse' href='#type' role='button'
                                           aria-expanded='false' aria-controls='type'>
                                            <p className='link-primary'> ❯ Filter by type </p></a>
                                        <div className='collapse' id='type'>
                                            <ul style={{listStyleType: "none"}}>
                                                { type.map((type) => {
                                                        return (
                                                            <li key={type.title}>
                                                            <input className='form-check-input filter-checks filter-checks-type'
                                                                   type='checkbox' id={type.title} name='type[]'
                                                                   value={type.title}></input>
                                                                <label className='form-check-label'
                                                                       htmlFor={type.title}> {type.title} </label><br/>
                                                            </li>
                                                        );
                                                    })
                                                }
                                            </ul>
                                        </div>
                                        <hr/>
                                    </div>

                                    <div>
                                        <a className='btn' data-bs-toggle='collapse' href='#specialism' role='button'
                                           aria-expanded='false' aria-controls='specialism'>
                                            <p className='link-primary'>❯ Filter by specialism </p>
                                        </a>
                                        <div className='collapse' id='specialism'>
                                            <ul style={{listStyleType: 'none'}}>
                                                { specialism.map((s) => {
                                                    return (
                                                        <li key={s.title}>
                                                            <input
                                                                className='form-check-input filter-checks filter-checks-specialism'
                                                                type='checkbox' id={s.title} name='specialism[]'
                                                                value={s.title}></input>
                                                                <label className='form-check-label'
                                                                       htmlFor={s.title}> {s.title} </label><br/>
                                                        </li>
                                                    );
                                                    })
                                                }
                                            </ul>
                                        </div>
                                        <hr/>
                                    </div>

                                </div>
                                <br/>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    );
};
