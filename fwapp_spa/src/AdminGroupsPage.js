import {useState} from "react";
import axios from "axios";
import logo from "./logo.svg";

function deleteGroup(token, id) {
    axios.get("http://www.uniquechange.com/fwApp/api/admin/groups_del.php?gid="+id, { headers: {"X-Auth-Token": token} })
        .then(response => {
            console.log("group deleted")
        })
        .catch(error => {
            console.log(error);
        });
}

function addGroup(token, name) {
    axios.get("http://www.uniquechange.com/fwApp/api/admin/groups_add.php?name="+name, { headers: {"X-Auth-Token": token} })
        .then(response => {
            console.log("group deleted")
        })
        .catch(error => {
            console.log(error);
        });
}

function fetchGroups(token, setGroups) {
    axios.get("http://www.uniquechange.com/fwApp/api/admin/groups_get.php", { headers: {"X-Auth-Token": token} })
        .then(response => {
            let data = response.data;
            setGroups(data);
        })
        .catch(error => {
            console.log(error);
        });
}

export const AdminGroups = () => {
    let [groups, setGroups] = useState([]);
    let [groupName, setGroupName] = useState("");

    var token = "6aba7069a7c3702fa88098807a301e31";

    if(groups.length === 0) {
        fetchGroups(token, setGroups);
    }

    return (
        <div>
            <table className="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                    { groups.map((g, ind) =>
                        <tr>
                            <td>{g.group.id}</td>
                            <td>{g.group.name}</td>
                            <td>
                                <button onClick={() => {
                                    deleteGroup(token, g.group.id);
                                    fetchGroups(token, setGroups);
                                }}>Delete
                                </button>
                            </td>

                            <td colSpan="4">
                                <table className="table mb-0">
                                    <thead>
                                        <th scope="col">#</th>
                                        <th>Question Set</th>
                                    </thead>
                                    <tbody>
                                        { g.questions.map((g, ind) =>
                                            <tr>
                                                <td>{g.id}</td>
                                                <td>{g.question_set_id}</td>
                                            </tr>
                                            )}
                                    </tbody>
                                </table>
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
        </div>
    );
};
