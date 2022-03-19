import {useState} from "react";
import axios from "axios";
import { useSelector} from 'react-redux'
import ReactModal from 'react-modal';
import {AdminNav} from "./AdminNav";

function fetchPhases(token, setPhases) {
    axios.get("http://www.uniquechange.com/fwApp/api/admin/phases_get.php", { headers: {"X-Auth-Token": token} })
        .then(response => {
            let data = response.data;
            console.log("Got phases: ", data);
            setPhases(data);
        })
        .catch(error => {
            console.log(error);
        });
}

export const AdminPhases = () => {
    let [phases, setPhases] = useState([]);
    let [activePhaseId, setActivePhaseId] = useState(null);

    const token = useSelector((state) => state.userDetails.token)


    if(phases.length === 0) {
        fetchPhases(token, setPhases);
    }

    let activePhase = phases.find((p) => p.ID === activePhaseId);

    return (
        <div>
            <AdminNav/>
            <table className="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Name</th>
                    <th>Edit</th>
                    {/*<th>Delete</th>*/}
                </tr>
                </thead>
                <tbody>
                    { phases.map((p, ind) =>
                        <tr key={ind}>
                            <td>{p.ID}</td>
                            <td>{p.title}</td>
                            <td>
                                <button className="btn btn-primary" onClick={() => {
                                    setActivePhaseId(p.ID);
                                }}>Edit</button>
                            </td>
                            {/*<td>*/}
                            {/*    <button className="btn btn-danger" onClick={() => {*/}
                            {/*        deleteGroup(token, g.group.id);*/}
                            {/*        fetchPhases(token, setPhases);*/}
                            {/*    }}>Delete</button>*/}
                            {/*</td>*/}
                        </tr>
                    )}
                </tbody>
            </table>

            { activePhase != null && <ReactModal isOpen={true}>
                <div className="container">
                    <h3>Editing phase: '{activePhase.title}'</h3>
                    <table className="table table-striped">
                        <thead>
                            <th scope="col">#</th>
                            <th>Question Set</th>
                            {/*<th>Delete</th>*/}
                        </thead>
                        <tbody>
                        { activePhase.question_sets.map((qs, ind) =>
                            <tr>
                                <td>{qs.ID}</td>
                                <td>{qs.title}</td>
                                {/*<td>*/}
                                {/*    <button className="btn btn-danger" onClick={() => {*/}
                                {/*        deleteQuestionSetFromGroup(token, activeGroupId, g.question_set_id).then(() => {*/}
                                {/*            fetchPhases(token, setPhases);*/}
                                {/*            setActiveGroupId(activeGroupId);*/}
                                {/*        });*/}
                                {/*    }}>Delete</button>*/}
                                {/*</td>*/}
                            </tr>
                        )}
                        </tbody>
                    </table>

                    {/*<div className="row col-4 mt-3">*/}
                    {/*    <div className="input-group mb-3">*/}
                    {/*        <input type="text" className="form-control" placeholder="Question set ID"*/}
                    {/*               aria-label="Question set id" aria-describedby="basic-addon2" onChange={(t) => setQuestionSetId(t.target.value)}/>*/}
                    {/*            <div className="input-group-append">*/}
                    {/*                <button className="btn btn-primary" onClick={() => {*/}
                    {/*                    addGroupQuestionSet(token, activeGroupId, questionSetId).then((res) => {*/}
                    {/*                        fetchPhases(token, setPhases);*/}
                    {/*                    });*/}
                    {/*                }}>Add Question Set</button>*/}
                    {/*            </div>*/}
                    {/*    </div>*/}
                    {/*</div>*/}
                    <button className="btn btn-danger" onClick={() => setActivePhaseId(null)}>Close</button>
                </div>
            </ReactModal>
            }
        </div>
    );
};
