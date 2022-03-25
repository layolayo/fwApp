import {useState} from "react";
import axios from "axios";
import { useSelector} from 'react-redux'
import ReactModal from 'react-modal';
import {AdminNav} from "./AdminNav";

function deleteGroupFromFacilitator(token, gid, facilitator) {
    return axios.get("https://facilitatedwriting.com/fwApp/api/admin/facilitators_group_del.php?email="+facilitator+"&gid="+gid, { headers: {"X-Auth-Token": token} })
        .then(response => {
            console.log("Deleted group from facilitator");
        })
        .catch(error => {
            console.log(error);
        });
}

function fetchFacilitators(token, setFacilitators) {
    axios.get("https://facilitatedwriting.com/fwApp/api/admin/facilitators_get.php", { headers: {"X-Auth-Token": token} })
        .then(response => {
            let data = response.data;
            console.log("Got groups: ", data);
            setFacilitators(data);
        })
        .catch(error => {
            console.log(error);
        });
}

function addFacilitatorGroup(token, email, gid) {
    return axios.get("https://facilitatedwriting.com/fwApp/api/admin/facilitators_group_add.php?email="+email+"&gid="+gid, { headers: {"X-Auth-Token": token} })
        .then(response => {
            console.log("Add group");
        })
        .catch(error => {
            console.log(error);
        });
}

export const AdminFacilitators = () => {
    let [facilitators, setFacilitators] = useState([]);
    let [activeFacilitator, setActiveFacilitator] = useState(null)
    let [groupId, setGroupId] = useState("")



    const token = useSelector((state) => state.userDetails.token)


    if(facilitators.length === 0) {
        fetchFacilitators(token, setFacilitators);
    }

    return (
        <div>
            <AdminNav/>
            <table className="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Email</th>
                    <th>Edit</th>
                    {/*<th>Delete</th>*/}
                </tr>
                </thead>
                <tbody>
                    { facilitators.map((f, ind) =>
                        <tr key={ind}>
                            <td>{ind + 1}</td>
                            <td>{f.facilitator.email}</td>
                            <td>
                                <button className="btn btn-primary" onClick={() => {
                                    setActiveFacilitator(f.facilitator.email);
                                }}>Edit</button>
                            </td>
                            {/*<td>*/}
                            {/*    <button className="btn btn-danger" onClick={() => {*/}
                            {/*        // deleteGroup(token, g.group.id);*/}
                            {/*        // fetchGroups(token, setFacilitators);*/}
                            {/*    }}>Delete</button>*/}
                            {/*</td>*/}
                        </tr>
                    )}
                </tbody>
            </table>
            {/*<input type="text" onChange={(t) => setGroupName(t.target.value)}/>*/}
            {/*<button onClick={() => {*/}
            {/*    addGroup(token, groupName);*/}
            {/*    fetchGroups(token, setFacilitators);*/}
            {/*}}>Add Group</button>*/}

            { activeFacilitator != null && <ReactModal isOpen={true}>
                <div className="container">
                    <h3>Editing User: '{activeFacilitator}'</h3>
                    { facilitators.find((f) => f.facilitator.email === activeFacilitator).groups.length > 0 &&
                        <table className="table table-striped">
                            <thead>
                            <th scope="col">#</th>
                            <th>Group name</th>
                            <th>Delete</th>
                            </thead>
                            <tbody>
                                { facilitators.find((f) => f.facilitator.email === activeFacilitator).groups.map((g) => {
                                        return <tr>
                                            <td>{g.id}</td>
                                            <td>{g.name}</td>
                                            <td>
                                                <button className="btn btn-danger" onClick={() => {
                                                    deleteGroupFromFacilitator(token, g.group_id, activeFacilitator).then(() => {
                                                        fetchFacilitators(token, setFacilitators);
                                                        setActiveFacilitator(activeFacilitator);
                                                    });
                                                }}>Delete</button>
                                            </td>
                                        </tr>
                                    })
                                }
                            </tbody>
                        </table>
                        }

                    <div className="row col-4 mt-3">
                        <div className="input-group mb-3">
                            <input type="text" className="form-control" placeholder="Group Id"
                                   aria-label="Question set id" aria-describedby="basic-addon2" onChange={(t) => setGroupId(t.target.value)}/>
                                <div className="input-group-append">
                                    <button className="btn btn-primary" onClick={() => {
                                        addFacilitatorGroup(token, activeFacilitator, groupId).then((res) => {
                                            fetchFacilitators(token, setFacilitators);
                                        });
                                    }}>Add Group</button>
                                </div>
                        </div>
                    </div>
                    <button className="btn btn-danger" onClick={() => setActiveFacilitator(null)}>Close</button>
                </div>
            </ReactModal>
            }
        </div>
    );
};
