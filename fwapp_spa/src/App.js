import './App.css';
import {BrowserRouter, Route, Routes, Navigate} from "react-router-dom";
import { BrowserTracing } from "@sentry/tracing";
import * as Sentry from "@sentry/react";
import {LoginPage} from "./LoginPage";
import {AdminGroups} from "./AdminGroupsPage";
import {useSelector} from "react-redux";
import {AdminFacilitators} from "./AdminFacilitators";
import {AdminQuestionSets} from "./AdminQuestionSets";
import {AdminPhases} from "./AdminPhasesPage";

export const BASE_URL = "/fwApp/html/admin"

Sentry.init({
    dsn: "https://c474b8e331584729b06eb608ac43c9b6@o1155143.ingest.sentry.io/6255121",
    integrations: [new BrowserTracing()],

    // We recommend adjusting this value in production, or using tracesSampler
    // for finer control
    tracesSampleRate: 1.0,
});

function App() {
    const token = useSelector((state) => state.userDetails.token)

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
