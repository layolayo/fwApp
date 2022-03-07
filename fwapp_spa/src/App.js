import './App.css';
import {PhasePage} from "./PhasePage";
import {BrowserRouter, Link, Redirect, Route, Routes, Navigate} from "react-router-dom";
import {LoginPage} from "./LoginPage";
import {AdminGroups} from "./AdminGroupsPage";
import {useDispatch, useSelector} from "react-redux";
import {AdminFacilitators} from "./AdminFacilitators";
import {AdminQuestionSets} from "./AdminQuestionSets";
import {AdminPhases} from "./AdminPhasesPage";

export const BASE_URL = "/fwApp/html/admin"

function App() {
    const token = useSelector((state) => state.userDetails.token)
    const dispatch = useDispatch()

  return (
      <BrowserRouter>
          {token == null &&
              <Routes>
                  <Route exact path={ BASE_URL + "/"} element={<Navigate to={BASE_URL+ "/login"}/>}/>
                  <Route exact path={ BASE_URL + "/login"} element={<LoginPage/>}/>
                  {/*<Route path="phase" element={<PhasePage/>}/>*/}
              </Routes>
          }
          { token != null &&
              <Routes>
                  <Route exact path={BASE_URL + "/"} element={<Navigate to={BASE_URL + "/admin/groups"}/>}/>
                  <Route exact path={BASE_URL + "/login"} element={<Navigate to={BASE_URL + "/admin/users"}/>}/>
                  {/*<Route path="phase" element={<PhasePage/>}/>*/}
                  <Route exact path={BASE_URL + "/admin/groups"} element={<AdminGroups/>}/>
                  <Route exact path={BASE_URL + "/admin/users"} element={<AdminFacilitators/>}/>
                  <Route exact path={BASE_URL + "/admin/question_sets"} element={<AdminQuestionSets/>}/>
                  <Route exact path={BASE_URL + "/admin/phases"} element={<AdminPhases/>}/>
              </Routes>
          }
      </BrowserRouter>
  );
}

export default App;
