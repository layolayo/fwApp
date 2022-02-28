import logo from './logo.svg';
import './App.css';
import axios from "axios";
import {useState} from "react";
import {PhasePage} from "./PhasePage";
import {BrowserRouter, Link, Route, Routes} from "react-router-dom";
import {LoginPage} from "./LoginPage";


function App() {
  return (
      <BrowserRouter>
        <div>
          <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
              <div className="container-fluid">
                  <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span className="navbar-toggler-icon"></span>
                  </button>
                  <div className="collapse navbar-collapse" id="navbarSupportedContent">
                      <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                          <li className="nav-item">
                              <Link className="nav-link active" to={"phase"}>Phase</Link>
                          </li>
                          <li className="nav-item">
                              <Link className="nav-link" to={"/about"}>About</Link>
                          </li>
                          <li className="nav-item">
                              <Link className="nav-link" to={"/account"}>Account</Link>
                          </li>
                          <li className="nav-item">
                              <Link className="nav-link" to={"/admin"}>ADMIN</Link>
                          </li>
                      </ul>
                      <form className="nav-item my-2 my-lg-0 dropdown">
                          <input className="form-control me-2" type="search" id="search" placeholder="Search" aria-label="Search"></input>
                          <ul className="dropdown-menu" id="result">
                          </ul>
                      </form>
                  </div>
              </div>
          </nav>
        </div>

          <Routes>
              <Route path="login" element={<LoginPage/>}/>
              <Route path="phase" element={<PhasePage/>}/>
          </Routes>
      </BrowserRouter>
  );
}

export default App;
