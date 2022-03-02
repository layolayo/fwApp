import {useState} from "react";
import axios from "axios";
import { useSelector, useDispatch } from 'react-redux'
import ReactModal from 'react-modal';
import {Link} from "react-router-dom";
import {BASE_URL} from "./App";
import {AdminNav} from "./AdminNav";

function deleteGroup(token, id) {
    axios.get("http://www.uniquechange.com/fwApp/api/admin/groups_del.php?gid="+id, { headers: {"X-Auth-Token": token} })
        .then(response => {
            console.log("group deleted")
        })
        .catch(error => {
            console.log(error);
        });
}

function deleteQuestionSetFromGroup(token, gid, qsid) {
    return axios.get("http://www.uniquechange.com/fwApp/api/admin/groups_questionset_del.php?gid="+gid+"&qsid="+qsid, { headers: {"X-Auth-Token": token} })
        .then(response => {
            console.log("group qs deleted")
        })
        .catch(error => {
            console.log(error);
        });
}

function addGroup(token, name) {
    return axios.get("http://www.uniquechange.com/fwApp/api/admin/groups_add.php?name="+name, { headers: {"X-Auth-Token": token} })
        .then(response => {
            console.log("group added")
        })
        .catch(error => {
            console.log(error);
        });
}

function addGroupQuestionSet(token, gid, qsid) {
    return axios.get("http://www.uniquechange.com/fwApp/api/admin/groups_questionset_add.php?gid="+gid+"&qsid="+qsid, { headers: {"X-Auth-Token": token} })
        .then(response => {
            console.log("group qs added")
        })
        .catch(error => {
            console.log(error);
        });
}

function fetchGroups(token, setGroups) {
    axios.get("http://www.uniquechange.com/fwApp/api/admin/groups_get.php", { headers: {"X-Auth-Token": token} })
        .then(response => {
            let data = response.data;
            console.log("Got groups: ", data);
            setGroups(data);
        })
        .catch(error => {
            console.log(error);
        });
}

export const AdminGroups = () => {
    let [groups, setGroups] = useState([]);
    let [groupName, setGroupName] = useState("")
    let [questionSetId, setQuestionSetId] = useState("")
    let [activeGroupId, setActiveGroupId] = useState(null)

    const token = useSelector((state) => state.userDetails.token)


    if(groups.length === 0) {
        fetchGroups(token, setGroups);
    }

    return (
        <div>
            <AdminNav/>
            <table className="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Name</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                    { groups.map((g, ind) =>
                        <tr>
                            <td>{g.group.id}</td>
                            <td>{g.group.name}</td>
                            <td>
                                <button className="btn btn-primary" onClick={() => {
                                    setActiveGroupId(g.group.id);
                                }}>Edit</button>
                            </td>
                            <td>
                                <button className="btn btn-danger" onClick={() => {
                                    deleteGroup(token, g.group.id);
                                    fetchGroups(token, setGroups);
                                }}>Delete</button>
                            </td>
                        </tr>
                    )}
                </tbody>
            </table>
            <input type="text" onChange={(t) => setGroupName(t.target.value)}/>
            <button onClick={() => {
                addGroup(token, groupName);
                fetchGroups(token, setGroups);
            }}>Add Group</button>

            { activeGroupId != null && <ReactModal isOpen={true}>
                <div className="container">
                    <h3>Editing Group: '{groups.find((g) => g.group.id === activeGroupId).group.name}'</h3>
                    <table className="table table-striped">
                        <thead>
                            <th scope="col">#</th>
                            <th>Question Set</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                        { groups.find((g) => g.group.id === activeGroupId).questions.map((g, ind) =>
                            <tr>
                                <td>{g.id}</td>
                                <td>{g.question_set_id}</td>
                                <td>
                                    {/*TODO: this wont update the ui yet?*/}
                                    <button className="btn btn-danger" onClick={() => {
                                        deleteQuestionSetFromGroup(token, activeGroupId, g.question_set_id).then(() => {
                                            fetchGroups(token, setGroups);
                                            setActiveGroupId(activeGroupId);
                                        });
                                    }}>Delete</button>
                                </td>
                            </tr>
                        )}
                        </tbody>
                    </table>

                    <div className="row col-4 mt-3">
                        <div className="input-group mb-3">
                            <input type="text" className="form-control" placeholder="Question set ID"
                                   aria-label="Question set id" aria-describedby="basic-addon2" onChange={(t) => setQuestionSetId(t.target.value)}/>
                                <div className="input-group-append">
                                    <button className="btn btn-primary" onClick={() => {
                                        addGroupQuestionSet(token, activeGroupId, questionSetId).then((res) => {
                                            fetchGroups(token, setGroups);
                                        });
                                    }}>Add Question Set</button>
                                </div>
                        </div>
                    </div>
                    <button className="btn btn-danger" onClick={() => setActiveGroupId(null)}>Close</button>
                </div>
            </ReactModal>
            }
        </div>
    );
};
