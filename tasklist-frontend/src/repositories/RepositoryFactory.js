
import UsersRepository from "./UsersService";
import NeedsRepository from "./NeedsService";
import CapabilitiesRepository from "./CapabilitiesService";
import TasksRepository from "./TasksService";

const repositories = {
    users: UsersRepository,
    needs: NeedsRepository,
    capabilities: CapabilitiesRepository,
    tasks: TasksRepository,
};

export const RepositoryFactory = {
    get: name => repositories[name]
};
